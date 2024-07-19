<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use Illuminate\Http\Request;

class ValidateBookingController extends Controller
{
    /**
     * Validate booking request and do nothing with it.
     *
     * @param BookingRequest $request The validated booking request.
     * @return void
     */
    public function __invoke(BookingRequest $request)
    {
        // Validate the booking request and do nothing with it.
        // This is a simple example of a controller that validates a request
        // and does not perform any action.
        $request->validated();
    }
}
