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
use App\Services\BookingService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class BookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

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
            'tour'            => Tour::with('packages.activities')->findOrFail($request->tour_id),
            'availability'    => Availability::findOrFail($availabilityId),
            'contact_methods' => ContactMethod::all()
        ]);
    }

    public function store(BookingRequest $request, Availability $availability)
    {
        $booking = $this->bookingService->store($request->validated());

        $payment = $this->bookingService->associateBookingWithPayment();

        $this->bookingService->updateAvailabilitySlot($availability->id);

        return redirect()->route('payment.show', ['id' => $payment->id]);    
    }
}
