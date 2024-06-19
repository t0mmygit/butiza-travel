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

        // Currently status only update to 'cancelled'
        $booking->status = 'cancelled';
        $booking->save();

        return redirect()->route('profile.history');
    }
}
