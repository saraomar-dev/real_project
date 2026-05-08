<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inspection;
use App\Models\Plot;
use Illuminate\Support\Facades\Storage;

class InspectionController extends Controller
{
   
public function index()
{
    $rentedPlots = Plot::with(['user', 'inspections'])
        ->where('status', 'rented')
        ->get();

    $allInspections = Inspection::with('plot')
        ->latest()
        ->get();

    return view('warden.inspections.index', compact('rentedPlots', 'allInspections'));
}

    /**
     * حفظ تقرير المعاينة (Compliance Audit)
     * تنفيذ للمتطلبات FR-08, FR-09, FR-36
     */
  public function store(Request $request) {
    $request->validate([
        'plot_id' => 'required|exists:plots,id',
        'status' => 'required',
        'notes' => 'required',
    ]);

    \App\Models\Inspection::create([
        'plot_id' => $request->plot_id,
        'user_id' => auth()->id(), 
        'status' => $request->status,
        'notes' => $request->notes,
        'has_pests' => $request->has('has_pests') ? 1 : 0,
    ]);

    // التعديل هنا: هينقلك لصفحة الجدول الرئيسية ويطلع رسالة خضراء
    return redirect()->route('warden.inspections.index')->with('success', '✅ Inspection report has been saved and added to the audit trail.');
}


    public function create(Plot $plot)
{
    // بنبعت بيانات الأرض للفورم عشان الواردن يعرف هو بيفتش على أنهي أرض
    return view('warden.inspections.create', compact('plot'));
}
}