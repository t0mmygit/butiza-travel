<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Day;
use App\Models\Tour;
use App\Models\Itinerary;

class TourController extends Controller
{
    public function index() 
    {
        return Inertia::render('Package', [
            'tours' => Tour::all(),
        ]);
    }

    public function show($tourId) 
    {
        $tour = Tour::findOrFail($tourId);

        $itinerary = Itinerary::where('tour_id', $tourId)->firstOrFail();

        $days = Day::where('itinerary_id', $itinerary->id)
            ->orderBy('day_number')
            ->get();

        // dd($tour, $itinerary, $days);

        return Inertia::render('Tour', [
            'tour' => $tour,
            'itinerary' => $itinerary,
            'day' => $days,
        ]);
    }

    public function reserve($tourId)
    {
        return Inertia::render('Reserve', [
            'tour' => Tour::where('id', $tourId)->firstOrFail()
        ]);
    }

    public function submitReservation(Request $request)
    {
        $validated = $request->validate([
            'date'           => 'required|date',
            'people'         => 'required|numeric',
            'notes'          => 'required|string',
            'first_name'     => 'required|string',
            'last_name'      => 'required|string',
            'email'          => 'required|string|email:rfc,dns|lowercase',
            'phone_number'   => 'required|numeric',
            'contact_option' => 'required|numeric'
        ]);
    }
}
