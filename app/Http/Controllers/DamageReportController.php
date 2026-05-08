<?php

namespace App\Http\Controllers;
use App\Models\DamageReport;
use Illuminate\Http\Request;

class DamageReportController extends Controller
{
   // public function store(Request $request){
   
  //  $imagePath = null;

   // if ($request->hasFile('image')) {
    //    $imagePath = $request->file('image')->store('damage', 'public');
   // }

   // DamageReport::create([
      //  'user_id' => auth()->id(),
      //  'tool_id' => $request->tool_id,
   //     'description' => $request->description,
       // 'image' => $imagePath,
   // ]);

   // return redirect()->back();
//}

public function store(Request $request)
{
    $imagePath = null;

    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('damage', 'public');
    }

    DamageReport::create([
        'user_id' => auth()->id() ?? 1,
        'tool_id' => $request->tool_id ?? 1,
        'description' => $request->description ?? 'test',
        'image' => $imagePath,
    ]);

    return redirect()->back();
}
public function index()
{
    $reports = DamageReport::all();
    return view('damage.index', compact('reports'));
}
public function addFine($id)
{
    $report = DamageReport::findOrFail($id);

    $report->fine = true;
    $report->save();

    return redirect()->back();
}
}
