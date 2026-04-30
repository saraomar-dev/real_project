<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Seed;
class SeedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {       $seeds=Seed::all();
        return view('seeds.index',compact('seeds'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('seeds.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $seed=$request->validate([
    'name' => 'required|string|max:255',
    'quantity' => 'required|integer|min:0',
    'expiry_date' => 'required|date',
]);
        Seed::create($seed);
        return redirect()->route('seeds.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Seed $seed)
    {

    if (auth()->user()->role !== 'admin') {
        abort(403);
    }

    return view('seeds.edit', compact('seed'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Seed $seed)
    {
        if (auth()->user()->role !== 'admin') {
        abort(403);
    }
    $data=$request->validate([
    'name' => 'required|string|max:255',
    'quantity' => 'required|integer|min:0',
    'expiry_date' => 'required|date',
]);

    $seed->update($data);

    return redirect()->route('seeds.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seed $seed)
    {
        if(auth()->user()->role!=='admin'){
        abort(403);
        }
        $seed->delete();
        return redirect()->route('seeds.index')
            ->with('success', 'User deleted successfully');
    }
}
