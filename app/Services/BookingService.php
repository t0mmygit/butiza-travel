<?php

namespace App\Services;

use App\Models\Availability;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class BookingService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected Booking $booking, 
    ) { }

    public function store(array $data, Availability $availability): array | string
    {
        try {
            DB::beginTransaction();
            
            $booking = $this->createBooking($data);
            
            $this->attachUserToBooking($booking);
            
            $this->updateAvailabilitySlot($availability);
            
            $payment = $this->associateBookingWithPayment($booking, Arr::get($data, 'amount'));

            $this->associateUserWithPayment($payment);

            DB::commit();

            return Crypt::encryptString($payment->id);
        } catch (\Exception $exception) {
            DB::rollBack();

            Debugbar::error($exception);

            return [
                'status' => config('constant.toast.error'),
                'message' => 'Something went wrong. Unable to proceed with payment.',
            ];
        }
    }

    private function createBooking(array $data): Booking
    {
        return Booking::create(Arr::except($data, 'amount'));
    }

    private function updateAvailabilitySlot(Availability $availability): void
    {
        $availability->increment('occupied_slot');
    }
    
    private function attachUserToBooking(Booking $booking): void
    {   
        if (! auth()->check()) return;
        
        $user = User::where('email', $booking->email)->firstOrFail();
        $user->bookings()->attach($booking->id);
    }

    private function associateBookingWithPayment(Booking $booking, float $amount): Payment
    {
        $payment = Payment::make([
            'amount' => $amount,
        ]);
        
        $payment->booking()->associate($booking);
        $payment->save();

        return $payment;
    }

    private function associateUserWithPayment(Payment $payment): void
    {
        if (! auth()->check()) return;

        $payment->user()->attach(auth()->id());
    }
}