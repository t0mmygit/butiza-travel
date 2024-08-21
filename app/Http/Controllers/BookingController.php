<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Availability;
use App\Models\Booking;
use App\Models\ContactMethod;
use App\Models\Payment;
use App\Models\Tour;
use App\Services\BookingService;
use Inertia\Inertia;
use Inertia\Response;

class BookingController extends Controller
{
    public function __construct(
        protected BookingService $bookingService
    ) {}

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $booking->update([
            'trip_status' => $request->input('trip_status'),
        ]);

        if ($booking->trip_status === Booking::STATUS_CANCELLED) {
            $booking->payment->update([
                'status' => Payment::STATUS_CANCELLED,
            ]);
        }

        return redirect(route('profile.history', absolute: false));
    }

    public function show(Tour $tour, Availability $availability): Response
    {   
        $tour->load([
            'packages.activities',
            'pickupLocation',
            'discount:id,type,percentage'
            ])->get();

        return Inertia::render('Tour/Book', [
            'tour'            => $tour,
            'availability'    => $availability,
            'contact_methods' => ContactMethod::all(),
            'flash'           => session()->only(['status', 'message']),
         ]);
    }

    public function store(BookingRequest $request, Availability $availability)
    {
        $response = $this->bookingService->store($request->validated(), $availability);

        return is_array($response)
                ? back()->with(['status' => $response['status'], 'message' => $response['message']])
                : redirect(route('payment.create', ['id' => $response], absolute: false));
    }
}