<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PlotShare;
use Symfony\Component\HttpFoundation\Response;

class RoleManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     * @return \Symfony\Component\HttpFoundation\Response
     */

public function handle(Request $request, Closure $next, string $role): Response
{
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $user = auth()->user();

    // 1. لو الصفحة المطلوبة هي "My Invitations" والأدمن داخل، ارفض فوراً
    // لأن الأدمن مستحيل يكون عنده دعوات
    if ($request->routeIs('sharing.my_invitations') && $user->role !== 'user') {
        abort(403, 'Admins and Wardens do not have invitations.');
    }

    // 2. الحصانة للأدمن في تصفح الأراضي فقط
    if ($user->role === 'admin') {
        return $next($request);
    }

    // 1. نجيب الـ ID صح سواء كان اسمه plot أو plot_id
$plotId = $request->route('plot') ?? $request->route('plot_id');

if ($plotId) {
    // 2. التعديل هنا: لو الـ plotId ده عبارة عن Object (زي ما بيحصل في روت الـ Resource) 
    // لازم نتأكد إننا بناخد الـ ID منه أو بنجيبه من الداتابيز صح
    $plot = ($plotId instanceof \App\Models\Plot) ? $plotId : \App\Models\Plot::find($plotId);

    // 3. نتحقق إن الـ plot موجود وإنه مش "مجموعة"
    if ($plot && !($plot instanceof \Illuminate\Database\Eloquent\Collection)) {
        
        if ($plot->status !== 'available') {
            $isOwner = $plot->user_id == $user->id;
            $isPartner = \App\Models\PlotShare::where('plot_id', $plot->id)
                            ->where('shared_with', $user->id)
                            ->where('status', 'accepted')
                            ->exists();

            if (!$isOwner && !$isPartner && !in_array($user->role, ['warden', 'admin'])) {
                abort(403, 'Unauthorized access to this plot.');
            }
        }
    }
}

    return $next($request);
}
}