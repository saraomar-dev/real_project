<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use App\Models\Task;
use App\Models\DamageReport;
use App\Models\IncidentReport;
use App\Models\Complaint;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 👨‍💼 ADMIN DASHBOARD
        if ($user && $user->role === 'admin') {

            return view('auth.dashboard', [
                'totalUsers' => User::count(),
                'admins' => User::where('role', 'admin')->count(),
                'users' => User::where('role', 'user')->count(),

                'tasksCount' => Task::count(),
                'doneTasks' => Task::where('status', 'done')->count(),
                'pendingTasks' => Task::where('status', 'pending')->count(),

                'logs' => AuditLog::latest()->take(10)->get(),

                'damages' => DamageReport::all(),
                'incidents' => IncidentReport::all(),

                // إضافات مهمة من النسخة الثانية
                'complaints' => Complaint::with(['user', 'plot'])
                    ->where('status', 'pending')
                    ->latest()
                    ->get(),
            ]);
        }

        // 👤 USER DASHBOARD
        return view('auth.dashboard', [
            'tasks' => Task::where('user_id', $user->id)->get(),
            'damages' => DamageReport::where('user_id', $user->id)->get(),
            'incidents' => IncidentReport::where('user_id', $user->id)->get(),
        ]);
    }
}