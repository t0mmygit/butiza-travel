<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function sendEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email:rfc,dns|lowercase',
        ]);
        
        $user = User::where('email', $request->email)->first();

        return $user;
    }

    public function sendPassword(Request $request)
    {
        $validated = $request->validate([
            'user'     => 'required',
            'password' => 'required|password|string'
        ]);
    }

    public function sendUserDetail(Request $request)
    {
        $validated = $request->validate([
            'email'                 => 'required|string|email:rfc,dns|lowercase',
            'first_name'            => 'required|string',
            'last_name'             => 'required|string',
            'password'              => 'required|confirmed|password|String',
            'password_confirmation' => 'required|password',
            'role'                  => 'required|string'
        ]);

        User::create($validated);

        return Inertia::render('Main');
    }
}
