<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lease;
use App\Models\Plot;
use Carbon\Carbon; // عشان نتعامل مع التواريخ بسهولة

class LeaseController extends Controller
{
    
public function index()
{
    $leases = Lease::where('user_id', auth()->id())
                ->with('plot') 
                ->latest()
                ->get()
                ->map(function ($lease) {
                    // بنحسب المبلغ هنا: المساحة * 10 (أو السعر اللي تحبيه)
                    $lease->total_price = ($lease->plot->area_sqm ?? 0) * 10;
                    return $lease;
                });

    return view('leases.index', compact('leases'));
}

    public function terminate($id) 
    {
        $lease = Lease::findOrFail($id);
        $lease->update(['status' => 'terminated']);
        $lease->plot->update(['status' => 'available']);
        
        return redirect()->back()->with('success', 'Lease terminated successfully.');
    }

    // الدالة الجديدة لتفعيل زرار الـ Renew
    public function renew(Request $request, $id) 
    {
        $lease = Lease::findOrFail($id);
        
        // فنية: بنزود مدة الإيجار سنة من تاريخ النهاية الحالي
        $newEndDate = Carbon::parse($lease->end_date)->addYear();
        
        $lease->update([
            'end_date' => $newEndDate,
            'status' => 'active'
        ]);

        return redirect()->back()->with('success', 'Lease renewed until ' . $newEndDate->toDateString());
    }

public function generateInvoice($lease)
{
    $area = $lease->plot->area_sqm; // تعديل اسم العمود
    $pricePerMeter = 10; 
    // بقية الكود بتاعك سليم...

    
    $totalAmount = $area * $pricePerMeter;

    // 2. تطبيق الـ Strategy Pattern (الخصم)
    if ($lease->user->role === 'volunteer') {
        $totalAmount = $totalAmount * 0.8; // خصم 20%
    }

    // 3. إنشاء الفاتورة في الداتابيز (زي الصورة اللي بعتيها)
    \App\Models\Invoice::create([
        'user_id' => $lease->user_id,
        'plot_id' => $lease->plot_id,
        'amount'  => $totalAmount,
        'status'  => 'unpaid',
        'due_date' => \Carbon\Carbon::now()->addDays(7), // تدفع خلال أسبوع
    ]);
}
}