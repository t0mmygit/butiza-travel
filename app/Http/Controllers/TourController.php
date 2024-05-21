<?php

namespace App\Http\Controllers;

use App\Models\ContactMethod;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Day;
use App\Models\Tour;
use App\Models\Itinerary;
use App\Models\Reservation;

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
        $tour = Tour::with('destinations')->findOrFail($tourId);

        $itinerary = Itinerary::where('tour_id', $tourId)->firstOrFail();

        $days = Day::where('itinerary_id', $itinerary->id)
            ->orderBy('day_number')
            ->get();

        return Inertia::render('Tour', [
            'tour' => $tour,
            'itinerary' => $itinerary,
            'day' => $days,
        ]);
    }

    public function reserve($tourId)
    {
        return Inertia::render('Reserve', [
            'tour' => Tour::where('id', $tourId)->firstOrFail(),
            'contact_methods' => ContactMethod::all()
        ]);
    }

    public function submitReservation(Request $request)
    {
        $validated = $request->validate([
            'tours_id'         => 'required|numeric',
            'contact_methods' => 'required|numeric',
            'preferred_date'   => 'required|date',
            'passenger'       => 'required|numeric',
            'note'            => 'string|nullable',
            'first_name'      => 'required|string',
            'last_name'       => 'required|string',
            'email'           => 'required|string|email:rfc,dns|lowercase',
            'phone_number'    => 'required|numeric'
        ]);

        $validated['contact_methods_id'] = $validated['contact_methods'];
        unset($validated['contact_methods']);

        $validated['preferred_date'] = date('Y-m-d', strtotime($validated['preferred_date']));

        Reservation::create($validated);

        return redirect(route('tour.show', $validated['tours_id']));
    }
}
