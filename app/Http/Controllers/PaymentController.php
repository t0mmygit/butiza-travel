<?php

namespace App\Http\Controllers;

use App\Events\BookingPaid;
use App\Http\Requests\PaymentUpdateRequest;
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

    public function create(Request $request, string $id): Response
    {
        // There was a plan to use $request to fetch the payment type...
        // IDEA: Polymorphism for the payment type
        $payment = $this->paymentService->getPayment($id);

        return Inertia::render('Payment', [
            'payment' => $payment,
        ]);
    }

    public function update(PaymentUpdateRequest $request, Payment $payment): RedirectResponse
    {
        if ($this->paymentService->isPaid($payment)) {
            return redirect(route('profile.history', ['model' => 'payment']))->with([
                'status' => config('constant.toast.info'),
                'message' => 'This payment has already been paid.',
            ]);
        }

        $response = $this->paymentService->updatePayment($request, $payment);

        return $response;
    }

    public function edit(Payment $payment): Response
    {
        // TODO: Encrypt the payment ID on the frontend
        // Or store the hash in the database
        return Inertia::render('Payment', [
            'payment' => $payment->load('booking.package.tour'),
        ]);
    }
}