<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Models\ToolReservation;
use Illuminate\Http\Request;

class ToolReservationController extends Controller
{
    public function create()
    {
        $tools = Tool::all();
        return view('reservations.create', compact('tools'));
    }

    public function store(Request $request)
    {
        $exists = ToolReservation::where('tool_id', $request->tool_id)
            ->where('reservation_date', $request->reservation_date)
            ->exists();

        if($exists){
            return back()->with('error', 'Tool already reserved');
        }

        ToolReservation::create([
            'tool_id' => $request->tool_id,
            'user_name' => auth()->user()->name,
            'reservation_date' => $request->reservation_date,
        ]);

        return redirect('/reservations');
    }

    public function index()
    {
        if(auth()->user()->role == 'admin'){
            $reservations = ToolReservation::with('tool')->get();
        } else {
            $reservations = ToolReservation::with('tool')
                ->where('user_name', auth()->user()->name)
                ->get();
        }

        return view('reservations.index', compact('reservations'));
    }
}