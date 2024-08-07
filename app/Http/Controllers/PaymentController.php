<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Inertia\Inertia;
use Inertia\Response;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {}

    public function show(Request $request, string $id): Response
    {
        // There was a plan to use $request to fetch the payment type...
        // IDEA: Polymorphism to for the payment type
        $payment = $this->paymentService->getPayment($id);

        return Inertia::render('Payment', [
            'payment' => $payment,
        ]);
    }

    public function update(Request $request, Payment $payment): RedirectResponse
    {
        // AUTHORIZATION: Check if user is authorized to update the payment

        // TODO: Check if payment is already paid
        // TODO: Validate payment details

        $payment->update([
            'method' => $request->method,
            'status' => 'paid',
        ]);

        // TODO: Event for notification, inbox message, administration notification

        return $this->paymentService->redirectAfterPayment();
    }
}