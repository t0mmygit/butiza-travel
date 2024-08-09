<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Availability;
use App\Models\Booking;
use App\Models\ContactMethod;
use App\Models\Tour;
use App\Services\BookingService;
use Inertia\Inertia;
use Inertia\Response;

class BookingController extends Controller
{
    public function __construct(
        protected BookingService $bookingService
    ) {}

    public function update(Booking $booking): RedirectResponse
    {
        $this->authorize('update', $booking);

        // Currently status only update to 'cancelled'
        $booking->status = 'cancelled';
        $booking->save();

        return redirect()->route('profile.history');
    }

    public function show(Request $request, $availabilityId): Response
    {   
        return Inertia::render('Tour/Book', [
            'tour'            => Tour::with('packages.activities')->findOrFail($request->tour_id),
            'availability'    => Availability::findOrFail($availabilityId),
            'contact_methods' => ContactMethod::all(),
            'flash'           => session()->only(['status', 'message']),
         ]);
    }

    public function store(BookingRequest $request, Availability $availability)
    {
        $paymentId = $this->bookingService->store($request->validated(), $availability);

        return redirect(route('payment.show', ['id' => $paymentId], absolute: true));    
    }
}