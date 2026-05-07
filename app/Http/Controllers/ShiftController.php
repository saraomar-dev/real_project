<?php

/*namespace App\Http\Controllers;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()

    {

        $shifts = Shift::all();

        return view('shifts.index', compact('shifts'));

    }

    public function store(Request $request)

    {

        Shift::create([

            'date' => $request->date,

            'time' => $request->time,

            'required_users' => $request->required_users,

        ]);

        return back();

    }

}*/

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::with('users')->get();
        return view('shifts.index', compact('shifts'));
    }

    public function store(Request $request)
    {
        if(auth()->user()->role !== 'admin'){
            abort(403);
        }

        Shift::create([
            'date' => $request->date,
            'time' => $request->time,
            'required_users' => $request->required_users,
        ]);

        return back();
    }
}