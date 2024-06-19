<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BookingPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, Booking $booking): Response
    {
        return $user->email === $booking->email
                    ? Response::allow()
                    : Response::deny('DENIED: Require Update Booking Policy');
    }
}
