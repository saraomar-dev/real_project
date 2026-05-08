<?php


namespace App\Http\Controllers;

use App\Models\SoilRecord;
use App\Models\Plot;
use Illuminate\Http\Request;

class SoilRecordController extends Controller // تم تغيير الاسم هنا ليطابق الملف
{
    public function index()
    {
        $user = auth()->user();

        // 1. فلترة السجلات: اليوزر يشوف أرضه بس، الإدارة تشوف كله
        if ($user->role === 'user') {
            $records = SoilRecord::whereHas('plot', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->latest()->get();
        } else {
            $records = SoilRecord::with('plot.user')->latest()->get();
        }

        // 2. جلب الأراضي للفورم
        if ($user->role === 'warden' || $user->role === 'admin') {
            $plots = Plot::all();
        } else {
            $plots = Plot::where('user_id', $user->id)->get();
        }

        return view('soil.index', compact('records', 'plots'));
    }

    public function store(Request $request)
    {
        // حماية: اليوزر العادي ممنوع يخزن سجلات
        if (auth()->user()->role === 'user') {
            return redirect()->back()->with('error', 'Only Garden Staff can record soil health.');
        }

        $request->validate([
            'plot_id' => 'required|exists:plots,id',
            'ph_level' => 'required|numeric|between:0,14',
            'fertilizer_type' => 'required|string|max:255',
            'crop_type' => 'required|string|max:255',
        ]);

        SoilRecord::create([
            'plot_id' => $request->plot_id,
            'ph_level' => $request->ph_level,
            'fertilizer_type' => $request->fertilizer_type,
            'crop_type' => $request->crop_type,
            'notes' => $request->notes,
            'record_date' => now(),
        ]);

        return redirect()->back()->with('success', 'Soil record saved successfully!');
    }

    public function edit($id)
    {
        // التأكد إن اللي داخل واردن أو آدمن
        if (auth()->user()->role === 'user') {
            abort(403);
        }

        $record = SoilRecord::findOrFail($id);
        $plots = Plot::all(); // عشان لو حابب يغير الأرض وهو بيعدل
        return view('soil.edit', compact('record', 'plots'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'ph_level' => 'required|numeric|between:0,14',
            'fertilizer_type' => 'required|string|max:255',
            'crop_type' => 'required|string|max:255',
        ]);

        $record = SoilRecord::findOrFail($id);
        $record->update($request->all());

        return redirect()->route('soil.index')->with('success', 'Soil record updated successfully!');
    }
}