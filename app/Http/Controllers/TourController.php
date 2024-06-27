<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Enums\GuideType;

use App\Models\Tour;
use App\Models\Reservation;
use App\Models\Availability;
use App\Models\Booking;
use App\Models\ContactMethod;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

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

        GuideType::getLabel($tour->guide_type);

        return Inertia::render('Tour/Index', [
            'tour' => $tour,
        ]);
    }

    public function showReserveForm(Request $request)
    {
        return Inertia::render('Tour/Reserve', [
            'tour'            => Tour::findOrFail($request->tour_id),
            'contact_methods' => ContactMethod::all()
        ]);
    }

    public function showBookingForm(Request $request, $availabilityId)
    {   
        return Inertia::render('Tour/Book', [
            'tour'            => Tour::findOrFail($request->tour_id),
            'availability'    => Availability::findOrFail($availabilityId),
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

    public function validateBooking(Request $request)
    {
        $request->validate([
            'tour_id'        => 'required|numeric',
            'contact_method' => 'required|numeric',
            'departure_date' => 'required|date',
            'finished_date'  => 'required|date',
            'adult'          => 'required_without:child|nullable|numeric|max:99',
            'child'          => 'required_without:adult|nullable|numeric|max:99',
            'note'           => 'string|nullable',
            'first_name'     => 'required|string',
            'last_name'      => 'required|string',
            'email'          => 'required|string|email:rfc,dns|lowercase',
            'phone_number'   => 'required|numeric',
        ], [
            'adult.required_without' => 'The traveller field is required.',
            'child.required_without' => 'The traveller field is required.',
        ]);
    }

    public function storeBooking(Request $request, $availabilityId)
    {
        $validated = $request->validate([
            'tour_id'        => 'required|numeric',
            'contact_method' => 'required|numeric',
            'departure_date' => 'required|date',
            'finished_date'  => 'required|date',
            'adult'          => 'required_without:child|nullable|numeric|max:99',
            'child'          => 'required_without:adult|nullable|numeric|max:99',
            'note'           => 'string|nullable',
            'first_name'     => 'required|string',
            'last_name'      => 'required|string',
            'email'          => 'required|string|email:rfc,dns|lowercase',
            'phone_number'   => 'required|numeric',
        ]);

        // contact_methods is used as error message
        $validated['contact_method_id'] = $validated['contact_method'];
        unset($validated['contact_method']);

        $validated['departure_date'] = date('Y-m-d', strtotime($validated['departure_date']));
        $validated['finished_date'] = date('Y-m-d', strtotime($validated['finished_date']));

        $booking = Booking::create($validated);
        $availability = Availability::find($availabilityId);
        $availability->increment('occupied_slot');

        
        if (Auth::check()) {
            $authenticatedUser = Auth::user();
            
            $user = User::find($authenticatedUser->id);
            $user->bookings()->attach($booking->id);
            
            return redirect(route('profile.history'));
        } 
            
        // NOTE: IF USER HAS AN ACCOUNT BUT IS NOT LOGGED IN DURING BOOKING
        // Associates bookings with the current user based on similar email addresses.
        // If bookings exist, find the current user and attach bookings to their profile
        // by matching the emails of the current user with the emails associated with the bookings.
        $currentUser = User::where('email', $validated['email'])->firstOrFail();
        $bookings = Booking::where('email', $validated['email'])->firstOrFail();
        if ($bookings) {
            $user = User::find($currentUser->id);
            $user->bookings()->attach($bookings->pluck('id'));
        }
        
        // Redirection need to be changed/Add confirmation message
        return redirect(route('tour.show', $validated['tour_id']));
    }
}
