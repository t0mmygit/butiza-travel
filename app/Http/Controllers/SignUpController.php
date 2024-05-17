<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SignUpController extends Controller
{
    public function sendEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email:rfc,dns|lowercase',
        ]);
    }

    public function sendUserDetail(Request $request)
    {
        $validated = $request->validate([
            'email'                 => 'required|string|email:rfc,dns|lowercase',
            'first_name'            => 'required|string',
            'last_name'             => 'required|string',
            'password'              => 'required|confirmed|string',
            'password_confirmation' => 'required|string',
            'role'                  => 'required|string'
        ]);

        User::create($validated);

        return Inertia::render('Main');
    }
}
