<?php

namespace App\Http\Controllers;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function storeRating(Request $request)

{
if (auth()->id() == $request->to_user_id) {

        return redirect()->back()->with('error', 'You cannot rate yourself');

    }
    Rating::create([

        'from_user_id' => auth()->id(),

        'to_user_id' => $request->to_user_id,

        'rating' => $request->rating,

        'comment' => $request->comment,

    ]);

    return redirect()->back();

}
}
