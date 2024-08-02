<?php

namespace App\Services;

use App\Models\Availability;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class BookingService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public Booking $booking, 
        public Payment $payment
    ) { }

    public function store(array $data, Availability $availability): string
    {
        $this->booking = Booking::create($data);

        $this->attachUserToBooking();
        
        $this->updateAvailabilitySlot($availability->id);

        return $this->associateBookingWithPayment();
    }

    public function updateAvailabilitySlot(int $availabilityId): void
    {
        $availability = Availability::find($availabilityId);
        $availability->increment('occupied_slot');
    }
    
    public function attachUserToBooking(): void
    {   
        if (! Auth::check()) return;
        
        $user = User::where('email', $this->booking->email)->firstOrFail();
        $user->bookings()->attach($this->booking->id);
    }

    public function associateBookingWithPayment(): string
    {
        $this->payment->booking()->associate($this->booking->id);
        $this->payment->save();

        return encrypt($this->payment->id);
    }

    public function isUserEmailExist(string $email): bool
    {
        return User::where('email', $email)->exists();
    }

    public function encrypt(string $id): string 
    {
        return Crypt::encryptString($id);
    }
}