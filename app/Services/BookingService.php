<?php

namespace App\Services;

use App\Models\Availability;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BookingService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public Booking $booking, 
        public Payment $payment
    ) { }

    public function store(array $data): Booking
    {
        $this->booking = Booking::create($data);

        $this->attachUserToBooking();

        return $this->booking;
    }

    public function updateAvailabilitySlot(int $availabilityId): void
    {
        $availability = Availability::find($availabilityId);
        $availability->increment('occupied_slot');
    }

    public function associateBookingWithPayment(): Payment
    {
        $this->payment->booking()->associate($this->booking->id);
        $this->payment->save();

        return $this->payment;
    }

    public function attachUserToBooking(): void
    {   
        if ($this->isUserEmailExist($this->booking->email)) {
            $user = User::where('email', $this->booking->email)->firstOrFail();
            $user->bookings()->attach($this->booking->id);
        }
    }

    public function isUserEmailExist(string $email): bool
    {
        return User::where('email', $email)->exists();
    }
}