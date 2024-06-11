<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
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
