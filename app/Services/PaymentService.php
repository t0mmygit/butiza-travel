<?php

namespace App\Services;

use App\Events\BookingPaid;
use App\Http\Requests\PaymentUpdateRequest;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class PaymentService
{
    public function isPaid(Payment $payment): bool
    {
        return $payment->status === Payment::STATUS_SUCCESSFUL;
    }

    public function getPayment(string $encryptedId): Payment | RedirectResponse
    {
        // TODO: Separation Of Concerns; handle exceptions separately
        try {
            $paymentIdentifier = $this->decrypt($encryptedId);
            $payment = Payment::with('booking.package.tour')->findOrFail($paymentIdentifier);
            
            return $payment;
        } catch (\Exception $exception) {
            return back()->with([
                'message' => 'Unable to proceed with payment!',
                'error' => $exception->getMessage(),
            ]);
        }
    }

    public function updatePayment(PaymentUpdateRequest $request, Payment $payment): RedirectResponse 
    {
        $payment->update($request->validated());

        return $this->handlePaymentBasedOnStatus($payment);
    }

    private function handlePaymentBasedOnStatus(Payment $payment): RedirectResponse
    {
        $status = $payment->status;

        if ($status === Payment::STATUS_SUCCESSFUL) {
            return $this->handleSuccessfulPayment($payment);
        }

        if ($status === Payment::STATUS_PENDING) {
            return $this->handlePendingPayment();
        }

        if ($status === Payment::STATUS_CANCELLED   ) {
            return $this->handleCancelledPayment($payment);
        }
    }

    private function handleSuccessfulPayment(Payment $payment): RedirectResponse
    {
        // TODO: Generate Invoice
        $booking = $payment->booking()->get()->first();

        $booking->update([
            'trip_status' => Booking::STATUS_CONFIRMED,
        ]);

        event(new BookingPaid($booking));

        return $this->redirectAfterSuccessfulPayment();
    }

    private function handlePendingPayment(): RedirectResponse
    {
        return to_route('profile.history', ['model' => 'payment']);
    }

    private function handleCancelledPayment(Payment $payment): RedirectResponse
    {
        $booking = $payment->booking()->get()->first();

        $booking->update([
            'trip_status' => Booking::STATUS_CANCELLED,
        ]);

        return to_route('profile.history', ['model' => 'payment'])->with([
            'status'  => config('constant.toast.info'),
            'message' => 'Payment Cancelled',
        ]);
    }

    private function redirectAfterSuccessfulPayment(): RedirectResponse
    {
        if (! Auth::check()) {
            return $this->defaultRedirect();
        }

        $roles = $this->getCurrentAuthenticatedUserRole();

        if ( $roles->contains(config('constant.role.customer')) ) {
            return to_route('profile.history')->with([
                'status'  => config('constant.toast.success'),
                'message' => 'Payment Successful',
            ]);
        }

        if ( $roles->contains(config('constant.role.partner')) ) {
            return to_route('partner-account')->with([
                'status'  => config('constant.toast.success'),
                'message' => 'Payment Successful',
            ]);
        }

        return $this->defaultRedirect();
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
            'status'  => $status ?? config('constant.toast.success'),
            'message' => $message ?? 'Payment Successful',
        ]);
    }
}