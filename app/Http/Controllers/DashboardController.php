<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use App\Models\Complaint;
use App\Models\Task;
use App\Models\DamageReport;
use App\Models\IncidentReport;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 👨‍💼 لو اللي داخل Admin: نبعت له كل إحصائيات السيستم
        if ($user->role === 'admin') {
            return view('auth.dashboard', [
                'totalUsers'   => User::count(),
                'admins'       => User::where('role', 'admin')->count(),
                'users'        => User::where('role', 'user')->count(),
                
                'tasksCount'   => Task::count(),
                'doneTasks'    => Task::where('status', 'done')->count(),
                'pendingTasks' => Task::where('status', 'pending')->count(),
                
                'logs'         => AuditLog::latest()->take(10)->get(),
                'damages'      => DamageReport::all(),
                'incidents'    => IncidentReport::all(),
                'complaints'   => Complaint::where('status', 'pending')->latest()->take(5)->get(),
            ]);
        }

        // 👤 لو مزارع عادي: نبعت له بياناته الشخصية بس
        return view('auth.dashboard', [
            'tasks'     => Task::where('user_id', $user->id)->get(),
            'damages'   => DamageReport::where('user_id', $user->id)->get(),
            'incidents' => IncidentReport::where('user_id', $user->id)->get(),
        ]);
    }
}