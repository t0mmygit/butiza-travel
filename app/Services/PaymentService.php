<?php

namespace App\Services;

use App\Models\Payment;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class PaymentService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        
    }

    public function getPayment(string $encryptedId): Payment | RedirectResponse
    {
        // TODO: Separation Of Concerns; handle exceptions separately
        try {
            $paymentIdentifier = $this->decrypt($encryptedId);
            $payment = Payment::with('booking.package.tour')->findOrFail($paymentIdentifier);
            
            return $payment;
        } catch (DecryptException $exception) {
            return back()->with([
                'message' => 'Unable to proceed with payment!',
                'error' => $exception->getMessage(),
            ]);
        } catch (\Exception $exception) {
            return back()->with([
                'message' => 'Unable to proceed with payment!',
                'error' => $exception->getMessage(),
            ]);
        }
    }

    public function redirectAfterPayment(): RedirectResponse
    {
        // dd('Redirecting after payment...');
        // TODO: Handle exception they are none of the conditions
        if (! Auth::check()) {
            return to_route(route: 'explore.index')->with([
                'status' => config('constant.toast.success'),
                'message' => 'Payment Successful!',
            ]);
        }

        if (Auth::user()->role === config('constant.user_roles.customer')) {
            return to_route(route: 'profile.history')->with([
                'status' => config('constant.toast.success'),
                'message' => 'Payment Successful!',
            ]);
        }

        if (Auth::user()->role === config('constant.user_roles.partner')) {
            return to_route(route: 'partner-account')->with([
                'status' => config('constant.toast.success'),
                'message' => 'Payment Successful!',
            ]);
        }
    }

    protected function decrypt(string $encryptedId): string
    {
        return Crypt::decryptString($encryptedId);
    }
}