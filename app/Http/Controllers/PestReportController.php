<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PestReport;
use App\Models\Plot;
use App\Models\User;
use App\Notifications\PestReportedNotification;
use Illuminate\Support\Facades\Notification;


class PestReportController extends Controller
{
    public function index()
    {
        $reports = PestReport::with(['plot.user', 'user'])->latest()->get();

        $plots = Plot::where(function($query) {
            $query->where('user_id', auth()->id())
                  ->orWhereHas('shares', function($q) {
                      $q->where('shared_with', auth()->id())->where('status', 'accepted');
                  });
        })->get();

        return view('pest_reports.index', compact('reports', 'plots'));
    }

   public function store(Request $request)
{
    // 1. التحقق من البيانات المدخلة
    $request->validate([
        'plot_id' => 'required|exists:plots,id',
        'pest_type' => 'required|string|max:255',
        'description' => 'required|string',
    ]);

    // 2. إنشاء البلاغ في قاعدة البيانات
    $report = PestReport::create([
        'plot_id' => $request->plot_id,
        'user_id' => auth()->id(),
        'pest_type' => $request->pest_type,
        'description' => $request->description,
        'status' => 'pending',
    ]);

    
    $staff = User::whereIn('role', ['admin', 'warden'])->get();

    $farmers = User::whereHas('plots', function($query) {
        $query->where('status', 'rented');
    })->get();

    $allToNotify = $staff->concat($farmers)->unique('id');

    // 4. إرسال التنبيه الفوري
    Notification::send($allToNotify, new PestReportedNotification($report));

    return redirect()->back()->with('success', 'Report submitted! A community-wide alert has been sent to all members.');
}




    

    public function resolve($id)
    {
        if (!in_array(auth()->user()->role, ['warden', 'admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $report = PestReport::findOrFail($id);
        $report->update(['status' => 'resolved']);

        return redirect()->back()->with('success', 'Pest report has been resolved!');
    }
}