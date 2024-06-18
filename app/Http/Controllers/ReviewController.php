<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Tour;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Tour $tour)
    {
        $user = $request->user();

        $validated = $request->validate([
            'booking_id' => 'required|numeric',
            'rating' => 'required|numeric',
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        // FOR (LOGGED IN) USER
        $review = Review::make([
            'booking_id' => $validated['booking_id'],
            'tour_id' => $tour->id,
            'rating' => $validated['rating'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'status' => false,
        ]);

        $user->reviews()->save($review);
    }
}
