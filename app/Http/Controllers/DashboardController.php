<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use App\Models\Complaint;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. حساب الإحصائيات
        $totalUsers = User::count();
        $admins = User::where('role', 'admin')->count();
        $users = User::where('role', 'user')->count();


        $logs = AuditLog::latest()->take(10)->get();

        $complaints = Complaint::with(['user', 'plot'])
                      ->where('status', 'pending')
                      ->latest()
                      ->get();

       
      // جربي ده لو الملف جوه فولدر اسمه auth
return view('auth.dashboard', compact(
    'totalUsers', 
    'admins', 
    'users', 
    'logs', 
    'complaints'
));
    }
}