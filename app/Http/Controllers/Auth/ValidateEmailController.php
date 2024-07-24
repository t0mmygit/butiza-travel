<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ValidateEmailController extends Controller
{
    public function __invoke(Request $request): void
    {
        $request->validate([
            'email' => ['required', 'string', 'email:rfc,dns', 'lowercase'],
        ]);

        User::where('email', $request->email)->firstOrFail();
    }
}