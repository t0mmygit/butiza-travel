<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Inertia\Inertia;
use Inertia\Response;

class PaymentController extends Controller
{
    public function show(Request $request, string $id): Response
    {
        try {
            $query = Crypt::decryptString($id);

            return Inertia::render('Payment', [
                'payment' => Payment::with('booking.package.tour')->findOrFail($query),
            ]);
        } catch (DecryptException $e) {
            return back()->with([
                'message' => 'Unable to proceed with payment!',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        Payment::findOrFail($id)->update([
            'method' => $request->method,
            'status' => 'paid',
        ]);

        return redirect()->route('explore.index');
    }

    public function validation(Request $request)
    {
        //Prototyping ONLY; refer proper validation for Payment Transaction
        $request->validate([
            'cardholder_name' => 'required|string|uppercase',
            'card_number' => 'required|string',
            'card_month' => 'required|numeric|min:1|max:12',
            'card_year' => 'required|numeric',
            'card_cvv' => 'required|numeric',
            'billing_address' => 'required|alpha_num',
            'postal_code' => 'required|numeric',
            'terms' => 'required'
        ]);
    }
}