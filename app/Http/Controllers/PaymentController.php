<?php

namespace App\Http\Controllers;

use App\Events\BookingPaid;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        // dd($payment);

        return Inertia::render('Payment', [
            'payment' => $payment,
        ]);
    }

    public function update(Request $request, Payment $payment): RedirectResponse
    {
        // AUTHORIZATION: Check if user is authorized to update the payment

        // TODO: Check if payment is already paid
        // What if they already paid?
        // TODO: Validate payment details

        $payment->update([
            'method' => $request->method,
            'status' => 'successful',
        ]);
        
        // event(new BookingPaid(auth()->user()));
        // TODO: Event for notification, inbox message, administration notification
        // [1] Notify Admin
        // [2] Notify User/Partner

        // TODO: Handle email notifications

        return $this->paymentService->redirectAfterPayment();
    }
}