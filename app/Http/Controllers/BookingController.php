<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;

class BookingController extends Controller
{
    public function update(Booking $booking): RedirectResponse
    {
        $this->authorize('update', $booking);

        // Need to modify to checks for genera; status update (not just cancelled)
        $booking->status = 'cancelled';

        return redirect(route('profile.history'));
    }
}
