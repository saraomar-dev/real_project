<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MarketplaceListing;

class MarketplaceController extends Controller
{
    /*public function index()
    {
        $items = MarketplaceListing::all();
        return view('marketplace.index', compact('items'));
    }*/
        public function index()
{
    $items = MarketplaceListing::where('user_id', auth()->id())
        ->orWhereHas('requests', function($q){
            $q->where('requester_id', auth()->id());
        })
        ->get();

    return view('marketplace.index', compact('items'));
}

    public function store(Request $request)
    {
        MarketplaceListing::create([
            'product_name' => $request->product_name,
            'quantity' => $request->quantity,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'user_id' => auth()->id(),
        ]);

        return redirect()->back();
    }
}