<?php

namespace App\Http\Controllers;

use App\Models\CustomerQuery;
use Illuminate\Http\Request;

class CustomerSupportController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|email:rfc,dns|lowercase',
            'phone_number' => 'required|numeric',
            'question' => 'required|string',
        ]);

        CustomerQuery::create($validated);
        
        return redirect()->intended('/');
    }
}
