<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\Models\User;

class SignUpController extends Controller
{
    public function sendEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email:rfc,dns|lowercase|unique'.User::class,
        ]);
    }

    public function sendUserDetail(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'password' => 'required|string',
            'confirm_password' => 'required|string'
        ]);

        User::create($validated);

        return redirect(route('main'));
    }
}
