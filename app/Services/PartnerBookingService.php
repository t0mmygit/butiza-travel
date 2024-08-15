<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class PartnerBookingService
{
    public function __construct(
        protected Booking $booking
    ) {}

    public function store(Request $request): string 
    {   
        $bookingData = $this->filterRequestData($request);

        try {
            DB::beginTransaction();
    
            $this->booking = Booking::create($bookingData);
    
            $this->attachUserToBooking();
    
            $payment = $this->associateBookingWithPayment($request->input('amount')); 
    
            DB::commit();
    
            return Crypt::encryptString($payment->id);
        } catch (\Exception $exception) {
            DB::rollBack();

            return [
                'message' => 'Unable to proceed with payment!',
                'error'   => $exception->getMessage(),
            ];
        }
    }

    private function filterRequestData(Request $request): array
    {
        return $request->only([
            'package_id',
            'contact_method_id',
            'discount_id',
            'pickup_location_id',
            'departure_date',
            'finished_date',
            'adult',
            'child',
            'note',
            'first_name',
            'last_name',
            'email',
            'phone_number',
        ]);
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
        $payment->amount = $amount;
        $payment->save();

        return $payment;
    }
}
