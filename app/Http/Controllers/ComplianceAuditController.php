<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plot;
use App\Models\ComplianceRecord; 
class ComplianceAuditController extends Controller
{
  
    public function create()
    {
        $plots = Plot::where('status', 'rented')->get();
        return view('compliance.create', compact('plots'));
    }

   
    public function store(Request $request)
    {
        $request->validate([
            'plot_id' => 'required|exists:plots,id',
            'status' => 'required|in:compliant,violation',
            'inspection_image' => 'nullable|image|max:2048',
        ]);

     
        
        return redirect()->back()->with('success', 'Inspection report submitted successfully.');
    }
}