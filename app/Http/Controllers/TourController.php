<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Enums\BookingStatus;
use App\Enums\GuideType;
use App\Models\Activity;
use App\Models\Tour;
use App\Models\Reservation;
use App\Models\Availability;
use App\Models\Booking;
use App\Models\ContactMethod;
use App\Models\Destination;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class TourController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Package', [
            'tours' => Tour::all(),
        ]);
    }

    public function show($tourId): Response
    {
        $tour = Tour::with([
            'activities',
            'availabilities' => function ($query) {
                $query->orderBy('departure_date', 'asc');
            },
            'destinations', 
            'highlights',
            'itinerary.days', 
            'note.subjects.bulletPoints',
            'reviews.user',
            'reviews.tour',
            'bookmarks',
        ])->findOrFail($tourId);

        return Inertia::render('Tour/Index', [
            'tour' => $tour,
        ]);
    }

    public function showReserveForm(Request $request): Response
    {
        return Inertia::render('Tour/Reserve', [
            'tour'            => Tour::findOrFail($request->tour_id),
            'contact_methods' => ContactMethod::all()
        ]);
    }

    public function submitReservation(Request $request)
    {
        $validated = $request->validate([
            'tour_id'        => 'required|numeric',
            'contact_method' => 'required|numeric',
            'preferred_date' => 'required|date',
            'passenger'      => 'required|numeric',
            'note'           => 'string|nullable',
            'first_name'     => 'required|string',
            'last_name'      => 'required|string',
            'email'          => 'required|string|email:rfc,dns|lowercase',
            'phone_number'   => 'required|numeric',
        ]);

        // contact_methods is used as error message
        $validated['contact_method_id'] = $validated['contact_method'];
        unset($validated['contact_method']);

        $validated['preferred_date'] = date('Y-m-d', strtotime($validated['preferred_date']));

        $reservation = Reservation::create($validated);
        if (Auth::check()) {
            $user = User::find(Auth::user()->id);
            $user->reservations()->attach($reservation->id);

            return redirect(route('profile.history'));
        }
        // Redirection need to be changed/Add confirmation message
        return redirect(route('tour.show', $validated['tour_id']));
    }
}
