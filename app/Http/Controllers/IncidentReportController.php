<?php

namespace App\Http\Controllers;
use App\Models\IncidentReport;
use Illuminate\Http\Request;

class IncidentReportController extends Controller
{
    public function store(Request $request)
{
    $imagePath = null;

    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('incidents', 'public');
    }

    IncidentReport::create([
        'user_id' => auth()->id(),
        'title' => $request->title,
        'description' => $request->description,
        'severity' => $request->severity,
        'image' => $imagePath,
    ]);

    return redirect()->back();
}
}
