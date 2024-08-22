<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Tour;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    public function store(ReviewRequest $request, Tour $tour, Booking $booking): RedirectResponse
    {
        $review = Review::make($request->validated());

        $review->tour()->associate($tour);
        $review->booking()->associate($booking);
        $review->user()->associate($request->user());

        $review->save();

        return redirect()->route('profile.history');
    }
}