<?php

namespace App\Services;

use App\Models\Availability;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
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

    public function store(array $validatedData, Availability $availability): string 
    {
        $bookingData = $this->filterValidatedData($validatedData);
        $amount = $validatedData['amount'];

        try {
            DB::beginTransaction();

            $this->booking = Booking::create($bookingData);

            $this->attachUserToBooking();

            $this->updateAvailabilitySlot($availability->id);

            $payment = $this->associateBookingWithPayment($amount);

            $this->associateUserWithPayment($payment);

            DB::commit();

            return Crypt::encryptString($payment->id);
        } catch (\Exception $exception) {
            DB::rollBack();

            return back()->with([
                'status' => config('constant.toast.error'),
                'message' => 'Unable to proceed with payment! ' . $exception->getMessage(),
            ]);
        }
    }

    private function filterValidatedData(array $data): array
    {
        return Arr::except($data, 'amount'); 
    }

    private function updateAvailabilitySlot(int $availabilityId): void
    {
        // TODO: Handle exceptions if availability is not found
        Availability::findOrFail($availabilityId)->increment('occupied_slot');
    }
    
    private function attachUserToBooking(): void
    {   
        if (! Auth::check()) return;
        
        $user = User::where('email', $this->booking->email)->firstOrFail();
        $user->bookings()->attach($this->booking->id);
    }

    private function associateBookingWithPayment(float $amount): Payment
    {
        $payment = new Payment();

        $payment->booking()->associate($this->booking->id);
        $payment->fill(['amount' => $amount]);
        $payment->save();

        return $payment;
    }

    private function associateUserWithPayment(Payment $payment): void
    {
        $payment->user()->attach(Auth::id());
    }
}