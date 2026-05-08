<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlotShare;
use App\Models\Plot;
use App\Models\User;
use App\Http\Controllers\InvoiceController; // تأكدي إن ده موجود

class PlotShareController extends Controller
{
    public function index()
    {
        // 1. حماية الكنترولر من الأدمن والواردن
        if (auth()->user()->role !== 'user') {
            abort(403, 'Unauthorized access.');
        }

        $userId = auth()->id();

        // 2. الدعوات المرسلة (بصفتي مالك)
        $sentInvites = PlotShare::whereHas('plot', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->with('plot')->get();

        // 3. الدعوات المستلمة (بصفتي شريك)
        $receivedInvites = PlotShare::where('shared_with', $userId)
                            ->with('plot.owner')
                            ->get();

        return view('sharing.index', compact('sentInvites', 'receivedInvites'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'plot_id' => 'required|exists:plots,id',
            'shared_with_email' => 'required|email|exists:users,email',
        ]);

        $userToInvite = User::where('email', $request->shared_with_email)->first();
        $plot = Plot::findOrFail($request->plot_id);

        if ($plot->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'You are not the owner of this plot.');
        }

        PlotShare::create([
            'plot_id' => $plot->id,
            'shared_with' => $userToInvite->id,
            'status' => 'pending',
            'share_percentage' => 50,
        ]);

        return redirect()->back()->with('success', 'Invitation sent successfully!');
    }

    public function accept($id)
{
    $share = PlotShare::findOrFail($id);
    $share->update(['status' => 'accepted']);

    $plot = $share->plot;

   
    if ($plot->status === 'rented') {
        
  
        \App\Models\Invoice::where('plot_id', $plot->id)
                            ->where('status', 'unpaid')
                            ->delete();

   
        $totalAmount = $plot->area_sqm * 10;
       
        $partners = \App\Models\PlotShare::where('plot_id', $plot->id)
                    ->where('status', 'accepted')
                    ->get();

        $totalPeople = 1 + $partners->count();
        $splitAmount = $totalAmount / $totalPeople;

        \App\Models\Invoice::create([
            'user_id' => $plot->user_id,
            'plot_id' => $plot->id,
            'amount'  => $splitAmount,
            'status'  => 'unpaid',
            'due_date' => now()->addDays(7),
        ]);

        foreach ($partners as $p) {
            \App\Models\Invoice::create([
                'user_id' => $p->shared_with,
                'plot_id' => $plot->id,
                'amount'  => $splitAmount,
                'status'  => 'unpaid',
                'due_date' => now()->addDays(7),
            ]);
        }
    }

    return redirect()->back()->with('success', 'Partnership accepted and invoices updated!');
}
    public function reject($id)
    {
        $share = PlotShare::findOrFail($id);
        $plotId = $share->plot_id;

    
        if ($share->shared_with !== auth()->id() && $share->plot->user_id !== auth()->id()) {
            abort(403);
        }

        $share->delete(); 

        $invoiceController = new InvoiceController();
        $invoiceController->generatePlotInvoices($plotId);

        return redirect()->back()->with('info', 'Invitation updated and invoice recalculated.');
    }
}