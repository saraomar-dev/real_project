<?php

namespace App\Http\Controllers;

use App\Models\Plot;
use Illuminate\Http\Request;

class PlotController extends Controller
{
    public function index()
    {
        $plots = Plot::all();
        return view('plots.index', compact('plots'));
    }

    public function create()
    {
        return view('plots.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plot_number'       => 'required|unique:plots',
            'area_sqm'          => 'required|numeric',
            'soil_quality'      => 'required|in:excellent,good,fair,poor',
            'sunlight_exposure' => 'required|integer|between:0,100',
        ]);

        Plot::create($validated);

        return redirect()->route('plots.index')->with('success', 'Plot has been added successfully');
    }



    public function edit(Plot $plot)
    {
        return view('plots.edit', compact('plot'));
    }

    public function update(Request $request, Plot $plot)
    {
        $validated = $request->validate([
            'plot_number'       => 'required|unique:plots,plot_number,' . $plot->id,
            'area_sqm'          => 'required|numeric',
            'soil_quality'      => 'required|in:excellent,good,fair,poor',
            'sunlight_exposure' => 'required|integer|between:0,100',
            'status'            => 'required|in:available,rented,maintenance',
        ]);

        $plot->update($validated);

        return redirect()->route('plots.index')->with('success', 'Plot updated successfully');
    }

    public function destroy(Plot $plot)
    {
        $plot->delete();
        return redirect()->route('plots.index')->with('success', 'Plot deleted successfully');
    }
}