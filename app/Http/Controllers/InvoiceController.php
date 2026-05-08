<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Plot;
use App\Models\PlotShare; 
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index()
{
    $user = auth()->user();

    if ($user->role === 'admin') {
        // الأدمن يشوف كل فواتير الناس
        $invoices = Invoice::with(['plot', 'user'])->latest()->get();
    } else {
        // اليوزر (سواء مالك أو مشارك) يشوف الفواتير اللي متسجلة باسمه هو بس
        $invoices = Invoice::where('user_id', $user->id)
            ->with(['plot'])
            ->latest()
            ->get();
    }

    return view('invoices.index', compact('invoices'));
}
    public function pay($id)
    {
        $invoice = Invoice::findOrFail($id);
        // التحقق من أن الشخص الذي يدفع هو صاحب الفاتورة
        if (auth()->id() !== (int)$invoice->user_id) { 
            abort(403, 'Unauthorized action.'); 
        }
        
        $invoice->update(['status' => 'paid']);
        return redirect()->back()->with('success', 'Invoice paid successfully!');
    }

    /**
     * دالة توليد الفواتير: يتم استدعاؤها عند حجز الأرض أو شهرياً
     * تدعم تقسيم الفاتورة 50/50 لو فيه شريك مقبول (FR-05)
     */
  public function generatePlotInvoices($plotId)
{
    $plot = Plot::findOrFail($plotId);

    // 1. حساب السعر بناءً على المساحة (التسعير الديناميكي)
    $baseRate = 50; // سعر المتر الأساسي
    $soilPremium = ($plot->soil_quality === 'excellent') ? 10 : (($plot->soil_quality === 'good') ? 5 : 0);
    
    // السعر الإجمالي للأرض (المساحة * (السعر + العلاوة))
    $totalPrice = $plot->area_sqm * ($baseRate + $soilPremium);

    // 2. تنظيف الفواتير القديمة غير المدفوعة للأرض دي
    Invoice::where('plot_id', $plotId)->where('status', 'unpaid')->delete();

    // 3. فحص وجود شريك مقبول
    $activeShare = PlotShare::where('plot_id', $plotId)->where('status', 'accepted')->first();

    if ($activeShare) {
        // حالة الشراكة: نقسم المبلغ 50%
        $splitAmount = $totalPrice / 2;

        // فاتورة المالك
        Invoice::create([
            'user_id' => $plot->user_id,
            'plot_id' => $plot->id,
            'amount'  => $splitAmount,
            'status'  => 'unpaid',
            'due_date' => now()->addDays(5)
        ]);

        // فاتورة الشريك
        Invoice::create([
            'user_id' => $activeShare->shared_with,
            'plot_id' => $plot->id,
            'amount'  => $splitAmount,
            'status'  => 'unpaid',
            'due_date' => now()->addDays(5)
        ]);
    } else {
        // حالة المالك لوحده: السعر كامل
        Invoice::create([
            'user_id' => $plot->user_id,
            'plot_id' => $plot->id,
            'amount'  => $totalPrice,
            'status'  => 'unpaid',
            'due_date' => now()->addDays(5)
        ]);
    }
}

public function downloadPDF($id)
{
    $invoice = \App\Models\Invoice::with(['user', 'plot'])->findOrFail($id);

    // بنفتح صفحة بسيطة فيها بيانات الفاتورة قابلة للطباعة
    return view('invoices.pdf_template', compact('invoice'));
}

        // فاتورة
}