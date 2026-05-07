<?php

/*namespace App\Http\Controllers;
use App\Models\VolunteerHour;
use Illuminate\Http\Request;

class VolunteerHourController extends Controller
{
    /*public function store(Request $request)

    {

        VolunteerHour::create([

            'user_id' => $request->user_id,

            'shift_id' => $request->shift_id,

            'hours' => $request->hours,

        ]);

        return back();

    }*/
     /*   public function store(Request $request)
{
    VolunteerHour::create([
        'user_id' => auth()->id(), // ✅ الصح
        'shift_id' => $request->shift_id,
        'hours' => $request->hours,
    ]);

    return back();
}
public function store(Request $request)
{
    $request->validate([
        'shift_id' => 'required|exists:shifts,id',
        'hours' => 'required|numeric|min:1',
    ]);

    VolunteerHour::create([
        'user_id' => auth()->id(),
        'shift_id' => $request->shift_id,
        'hours' => $request->hours,
    ]);

    return back();
}
    public function create()
{
    return view('volunteer.create');
}
}*/

namespace App\Http\Controllers;

use App\Models\VolunteerHour;
use Illuminate\Http\Request;

class VolunteerHourController extends Controller
{
    public function create()
    {
        return view('volunteer.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'shift_id' => 'required|exists:shifts,id',
            'hours' => 'required|numeric|min:1',
        ]);

        VolunteerHour::create([
            'user_id' => auth()->id(),
            'shift_id' => $request->shift_id,
            'hours' => $request->hours,
        ]);

        return back();
    }
}