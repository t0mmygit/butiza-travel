<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;

class PartnerBookingService
{
    public function __construct(
        protected Booking $booking
    ) {}

    public function store(array $data): string
    {
        $this->booking = Booking::create($data);

        $this->attachUserToBooking();

        return $this->associateBookingWithPayment();
    }

    public function attachUserToBooking()
    {
        $user = User::where('email', $this->booking->email)->firstOrFail();
        $user->bookings()->attach($this->booking->id);
    }

    public function associateBookingWithPayment(): string
    {
        $payment = new Payment();

        $payment->booking()->associate($this->booking->id);
        $payment->save();

        return Crypt::encryptString($payment->id);
    }
}
