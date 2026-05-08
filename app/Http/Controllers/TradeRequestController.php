<?php

namespace App\Http\Controllers;
use App\Models\TradeRequest;
use Illuminate\Http\Request;

class TradeRequestController extends Controller
{
    public function store($listingId)

    {

        TradeRequest::create([

            'listing_id' => $listingId,

            'requester_id' => auth()->id(),

            'status' => 'pending'

        ]);

        return redirect()->back();

    }
    
public function accept($id)
{
    $request = TradeRequest::findOrFail($id);

    $request->update([
        'status' => 'accepted'
    ]);

    $user = \App\Models\User::find($request->requester_id);
    $user->karma += 10;
    $user->save();

    return redirect()->back();
}
public function reject($id)
{
    $request = TradeRequest::findOrFail($id);

    $request->update([
        'status' => 'rejected'
    ]);

    return redirect()->back();
}
public function indexRequests()
{
    $requests = TradeRequest::all();
    return view('marketplace.requests', compact('requests'));
}

}
