<?php

namespace App\Http\Controllers;
use App\Http\Controllers\UserController;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
    $admins = User::where('role', 'admin')->count();
    $users = User::where('role', 'user')->count();
    $logs = AuditLog::latest()->take(10)->get();

    return view('auth.dashboard', compact('logs','totalUsers',
        'admins',
        'users'));
    }
}
