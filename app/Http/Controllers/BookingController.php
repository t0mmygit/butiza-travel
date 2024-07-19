<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Availability;
use App\Models\Booking;
use App\Models\ContactMethod;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class BookingController extends Controller
{
    public function update(Booking $booking): RedirectResponse
    {
        $this->authorize('update', $booking);

        // Currently status only update to 'cancelled'
        $booking->status = 'cancelled';
        $booking->save();

        return redirect()->route('profile.history');
    }

    public function show(Request $request, $availabilityId)
    {   
        return Inertia::render('Tour/Book', [
            'tour'            => Tour::with('packages')->findOrFail($request->tour_id),
            'availability'    => Availability::findOrFail($availabilityId),
            'contact_methods' => ContactMethod::all()
        ]);
    }

    public function store(BookingRequest $request, $availabilityId)
    {
        $validated = $request->validated();

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
