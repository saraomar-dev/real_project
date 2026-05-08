<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plot;
use App\Models\Complaint;
use App\Models\User;
use App\Notifications\ComplaintNotification;

class ComplaintController extends Controller
{
    // 1. فتح صفحة تقديم الشكوى
    public function create(Request $request)
    {
        $plot_id = $request->query('plot_id');

        if (!$plot_id) {
            return redirect()->back()->with('error', 'Plot ID is required.');
        }

        $plot = Plot::findOrFail($plot_id);

        return view('complaints.create', compact('plot'));
    }

   
    public function store(Request $request)
    {
        $request->validate([
            'plot_id' => 'required|exists:plots,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

       
        $complaint = Complaint::create([
            'plot_id' => $request->plot_id,
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'pending',
        ]);

    
        $admin = User::where('role', 'admin')->first();

        if ($admin) {
            $admin->notify(new ComplaintNotification($complaint));
        }

        return redirect('/plots/' . $request->plot_id)
            ->with('success', 'Your issue has been reported to the administration.');
    }

   
    public function resolve($id)
    {
        $complaint = Complaint::findOrFail($id);

        $complaint->status = 'resolved';

        $complaint->save();

        return redirect()->back()
            ->with('success', 'Complaint resolved successfully.');
    }
}