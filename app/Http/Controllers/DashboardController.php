<?php

namespace App\Http\Controllers;
use App\Http\Controllers\UserController;
use App\Models\User;
use App\Models\AuditLog;
use App\Models\Task;
use App\Models\DamageReport;

use App\Models\IncidentReport;

use Illuminate\Http\Request;
class DashboardController extends Controller
{
   /* public function index()
    {
        $totalUsers = User::count();
    $admins = User::where('role', 'admin')->count();
    $users = User::where('role', 'user')->count();
    $logs = AuditLog::latest()->take(10)->get();

    //return view('auth.dashboard', compact('logs','totalUsers',
     //   'admins',
    //    'users'));
   // }
   

    $damagesCount = DamageReport::count();

    $incidentsCount = IncidentReport::count();

    return view('auth.dashboard', compact(

        'logs','totalUsers','admins','users',

        'damagesCount','incidentsCount'

    ));
    }
//extra
public function adminDashboard(Request $request)

{

    $damages = DamageReport::all();

    $incidents = IncidentReport::all();

    return view('admin.dashboard', compact('damages', 'incidents'));

}*/
/*public function index()
{
    $totalUsers = User::count();
    $admins = User::where('role', 'admin')->count();
    $users = User::where('role', 'user')->count();
$tasksCount = Task::count();
$doneTasks = Task::where('status', 'done')->count();
$pendingTasks = Task::where('status', 'pending')->count();
    $logs = AuditLog::latest()->take(10)->get();

    $damages = DamageReport::all();
    $incidents = IncidentReport::all();

    $damagesCount = DamageReport::count();
    $incidentsCount = IncidentReport::count();

    return view('auth.dashboard', compact(
        'totalUsers',
        'admins',
        'users',
        'logs',
        'damages',
        'incidents',
        'damagesCount',
        'incidentsCount',
        'tasksCount',
        'doneTasks',
        'pendingTasks'
    ));
}*/
/*public function index()
{
    $totalUsers = User::count();
    $admins = User::where('role', 'admin')->count();
    $users = User::where('role', 'user')->count();

    $tasksCount = Task::count();
    $doneTasks = Task::where('status', 'done')->count();
    $pendingTasks = Task::where('status', 'pending')->count();

    $logs = AuditLog::latest()->take(10)->get();

    $damages = DamageReport::all();
    $incidents = IncidentReport::all();

    return view('auth.dashboard', compact(
        'totalUsers',
        'admins',
        'users',
        'tasksCount',
        'doneTasks',
        'pendingTasks',
        'logs',
        'damages',
        'incidents'
    ));
}*/
public function index()
{
    // 👨‍💼 ADMIN DASHBOARD
    if (auth()->user()->role === 'admin') {

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
        ]);
    }

    // 👤 USER DASHBOARD
    return view('auth.dashboard', [
        'tasks' => Task::where('user_id', auth()->id())->get(),
        'damages' => DamageReport::where('user_id', auth()->id())->get(),
        'incidents' => IncidentReport::where('user_id', auth()->id())->get(),
    ]);
}

}
