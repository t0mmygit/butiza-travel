<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function sendEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email:rfc,dns|lowercase',
        ]);
        
        $user = User::where('email', $request->email)->first();

        return $user ? $user->email : '';
    }

    public function sendPassword(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'     => 'required',
            'password' => 'required|string'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'password' => 'The provided credentials do not match our records.', 
        ])->onlyInput('password');
    }

    public function sendUserDetail(Request $request)
    {
        $request->validate([
            'email'                 => 'required|string|email:rfc,dns|lowercase|unique:users',
            'first_name'            => 'required|string',
            'last_name'             => 'required|string',
            'password'              => 'required|confirmed', 
            // Password Rule for password
            'password_confirmation' => 'required',
        ]);

        $user = User::create([
            'name'     => $request->first_name. ' ' .$request->last_name,
            'email'    => $request->email,
            'password' => Hash::make($request->password)
        ]);

        Auth::login($user);

        return redirect()->intended('/');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        // What is Auth::guard() anyway

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');   
    }
}
