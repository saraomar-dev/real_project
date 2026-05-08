<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lease;
use App\Models\Plot;
use Carbon\Carbon;

class LeaseController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Scoping: الأدمن والواردن بيشوفوا كل العقود، اليوزر بيشوف عقوده بس
        if (in_array($user->role, ['admin', 'warden'])) {
            $leasesQuery = Lease::with(['plot', 'user'])->latest();
        } else {
            $leasesQuery = Lease::where('user_id', $user->id)->with('plot')->latest();
        }

        $leases = $leasesQuery->get()->map(function ($lease) {
            $lease->total_price = ($lease->plot->area_sqm ?? 0) * 10;
            return $lease;
        });

        return view('leases.index', compact('leases'));
    }

    public function terminate($id) 
    {
        // حماية: التأكد أن الأدمن فقط هو من ينهي العقد (حسب الـ SRS)
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action. Only admins can terminate leases.');
        }

        $lease = Lease::findOrFail($id);
        $lease->update(['status' => 'terminated']);
        $lease->plot->update(['status' => 'available']);
        
        return redirect()->back()->with('success', 'Lease terminated successfully.');
    }

    public function renew(Request $request, $id) 
    {
        // حماية: التأكد أن الأدمن فقط هو من يجدد العقد (حسب الـ SRS)
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action. Only admins can renew leases.');
        }

        $lease = Lease::findOrFail($id);
        $newEndDate = Carbon::parse($lease->end_date)->addYear();
        
        $lease->update([
            'end_date' => $newEndDate,
            'status' => 'active'
        ]);

        return redirect()->back()->with('success', 'Lease renewed until ' . $newEndDate->toDateString());
    }

    public function generateInvoice($lease)
    {
        $area = $lease->plot->area_sqm;
        $pricePerMeter = 10; 
        $totalAmount = $area * $pricePerMeter;

        if ($lease->user->role === 'volunteer') {
            $totalAmount = $totalAmount * 0.8; // Strategy Pattern: 20% Discount
        }

        \App\Models\Invoice::create([
            'user_id' => $lease->user_id,
            'plot_id' => $lease->plot_id,
            'amount'  => $totalAmount,
            'status'  => 'unpaid',
            'due_date' => \Carbon\Carbon::now()->addDays(7),
        ]);
    }
}