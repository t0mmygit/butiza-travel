<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Models\BulletPoint;
use App\Models\ContactMethod;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Day;
use App\Models\Tour;
use App\Models\Itinerary;
use App\Models\Subject;
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
        $tour = Tour::with([
            'activities',
            'availabilities',
            'destinations', 
            'highlights',
            'itineraries.days', 
            'note.subjects.bulletPoints'
        ])->findOrFail($tourId);

        return Inertia::render('Tour', [
            'tour' => $tour,
        ]);
    }

    public function showReserveForm(Request $request)
    {
        return Inertia::render('Reserve', [
            'tour'            => Tour::findOrFail($request->tour_id),
            'contact_methods' => ContactMethod::all()
        ]);
    }

    public function showBookingForm(Request $request, $availabilityId)
    {   
        return Inertia::render('Book', [
            'tour'            => Tour::findOrFail($request->tour_id),
            'availability'    => Availability::findOrFail($availabilityId),
            'contact_methods' => ContactMethod::all()
        ]);
    }

    public function submitReservation(Request $request)
    {
        $validated = $request->validate([
            'tour_id'        => 'required|numeric',
            'contact_methods' => 'required|numeric',
            'preferred_date'  => 'required|date',
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

    public function validateBooking(Request $request)
    {
        $validated = $request->validate([
            'tour_id'        => 'required|numeric',
            'contact_methods' => 'required|numeric',
            'departure_date'  => 'required|date',
            'passenger'       => 'required|numeric',
            'note'            => 'string|nullable',
            'first_name'      => 'required|string',
            'last_name'       => 'required|string',
            'email'           => 'required|string|email:rfc,dns|lowercase',
            'phone_number'    => 'required|numeric'
        ]);
    }
}
