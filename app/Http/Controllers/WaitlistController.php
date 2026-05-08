<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Waitlist;
use App\Models\Plot;
use App\Models\Lease;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

class WaitlistController extends Controller
{
    // 1. اليوزر يضيف نفسه للقائمة
    public function store()
    {
        $user = auth()->user();

        if ($user->role == 'admin' || $user->role == 'warden') {
            return redirect()->back()->with('error', 'Admins and Wardens cannot join the waitlist.');
        }

        $exists = Waitlist::where('user_id', $user->id)
                          ->where('status', 'waiting')
                          ->exists();

        if (!$exists) {
            Waitlist::create([
                'user_id' => $user->id,
                'status' => 'waiting'
            ]);
            return redirect()->back()->with('success', 'You have been added to the waitlist!');
        }

        return redirect()->back()->with('info', 'You are already on the waitlist.');
    }

    // عرض الويتلست
    public function index()
    {
        $waitlist = $this->getSortedWaitlist();
        return view('waitlist.index', compact('waitlist'));
    }

    // حساب ترتيب اليوزر
    public static function getUserPosition()
    {
        $controller = new self();
        $orderedList = $controller->getSortedWaitlist();
        $position = $orderedList->search(fn($item) => $item->user_id == auth()->id());
        return $position !== false ? $position + 1 : null;
    }

    // --- الدالة اللي فيها الحل كله ---
    public function assignPlot($waitlistId)
    {
        $waitlistItem = Waitlist::findOrFail($waitlistId);
        $user = $waitlistItem->user;

        $plot = Plot::where('status', 'available')->first();

        if (!$plot) {
            return back()->with('error', 'No vacant plots available at the moment.');
        }

        DB::transaction(function () use ($plot, $user, $waitlistItem) {
            // A. تحديث حالة الأرض وربطها باليوزر
            $plot->update([
                'user_id' => $user->id,
                'status'  => 'rented'
            ]);

            // B. إنشاء العقد (Lease) - ده اللي هيملى صفحة الـ Lease عندك
            $lease = Lease::create([
                'user_id'    => $user->id,
                'plot_id'    => $plot->id,
                'start_date' => now(),
                'end_date'   => now()->addYear(),
                'status'     => 'active',
            ]);

            // C. توليد الفاتورة فوراً عشان تظهر لليوزر في صفحة الفواتير
            $invoiceController = new InvoiceController();
            $invoiceController->generatePlotInvoices($plot->id);

            // D. حذف من الويتلست
            $waitlistItem->delete();
        });

        return redirect()->back()->with('success', "Success! Plot #{$plot->plot_number} assigned to {$user->name}. Lease and Invoice created.");
    }

  private function getSortedWaitlist()
{
    return Waitlist::with('user')
        ->where('status', 'waiting')
        ->get()
        ->map(function ($entry) {
            if (!$entry->user) {
                $entry->priority_score = 0;
                return $entry;
            }

            // 1. حساب فرق الأيام (التأكد إنه دايماً موجب)
            // نستخدم absolute value عشان نضمن إن مفيش سالب لو التاريخ فيه مشكلة
            $residencyDays = abs(now()->diffInDays($entry->user->created_at));

            // 2. القراءة من عمود "karma" اللي لقيتيه في الداتابيز
            $contributionScore = $entry->user->karma ?? 0;

            // 3. المعادلة النهائية: (نقاط الكارما) + (أيام العضوية / 10)
            // استخدمنا round عشان الرقم يطلع نظيف (مثلاً 5.3 بدل 5.2999)
            $entry->priority_score = round($contributionScore + ($residencyDays / 10), 1);

            return $entry;
        })
        ->sortByDesc('priority_score') // الترتيب: الأعلى سكور هو اللي في الأول
        ->values();
}

    public function destroy($id)
    {
        $waitlistItem = Waitlist::findOrFail($id);

        if (auth()->id() !== $waitlistItem->user_id && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $waitlistItem->delete();
        return redirect()->back()->with('success', 'You have been removed from the waitlist successfully.');
    }
}