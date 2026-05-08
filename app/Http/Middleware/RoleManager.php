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

public function handle(Request $request, Closure $next, ...$roles): Response
{
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $user = auth()->user();

    // لو الـ roles مبعوتة (زي admin,warden) بنشيك لو اليوزر معاه واحدة منهم
    if (!empty($roles) && !in_array($user->role, $roles)) {
        abort(403, 'This action is restricted to ' . implode(' or ', $roles));
    }

    // الحصانة للأدمن في باقي السيستم
    if ($user->role === 'admin') {
        return $next($request);
    }

    return $next($request);
}
}