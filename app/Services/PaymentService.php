<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
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
        if (! Auth::check()) {
            $this->defaultRedirect();
        }

        $roles = $this->getCurrentAuthenticatedUserRole();

        if ( $roles->contains(config('constant.role.customer')) ) {
            return to_route('profile.history')->with([
                'status' => config('constant.toast.success'),
                'message' => 'Payment Successful!',
            ]);
        }

        if ( $roles->contains(config('constant.role.partner')) ) {
            return to_route('partner-account')->with([
                'status' => config('constant.toast.success'),
                'message' => 'Payment Successful!',
            ]);
        }

        $this->defaultRedirect();
    }

    private function getCurrentAuthenticatedUserRole(): Collection
    {
        return Auth::user()->getRoleNames();
    }

    private function decrypt(string $encryptedId): string
    {
        return Crypt::decryptString($encryptedId);
    }

    private function defaultRedirect(string $status = null, string $message = null): RedirectResponse
    {
        return to_route('explore.index')->with([
            'status' => $status ?? config('constant.toast.success'),
            'message' => $message ?? 'Payment Successful!',
        ]);
    }
}