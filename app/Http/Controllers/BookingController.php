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
            'contact_methods' => ContactMethod::all(),
        ]);
    }

    public function store(BookingRequest $request, Availability $availability)
    {
        $parameter = $this->bookingService->store($request->validated(), $availability);

        return redirect()->route('payment.show', ['id' => $parameter]);    
    }
}