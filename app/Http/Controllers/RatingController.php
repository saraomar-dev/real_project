<?php

namespace App\Http\Controllers;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function storeRating(Request $request)
{
    // 1. التأكد إن التقييم موجود وقيمته بين 1 و 5
    $request->validate([
        'to_user_id' => 'required|exists:users,id',
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:255',
    ]);

    if (auth()->id() == $request->to_user_id) {
        return redirect()->back()->with('error', 'You cannot rate yourself');
    }

    // 2. التخزين
    \App\Models\Rating::create([
        'from_user_id' => auth()->id(),
        'to_user_id' => $request->to_user_id,
        'rating' => $request->rating,
        'comment' => $request->comment,
    ]);

    return redirect()->back()->with('success', 'Thank you for your rating!');
}
}
