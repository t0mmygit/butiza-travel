<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ValidateEmailController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email:rfc,dns', 'lowercase'],
        ]);

        $user = User::where('email', $request->email)->first();

        return $user ? $user->email : '';
        // return redirect(route('home'))->with(['isUser', $isInstanceOfUser]);
    }
}