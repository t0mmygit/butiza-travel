<?php

namespace App\Http\Controllers;

use App\Events\NewPartnerRegistered;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email:rfc,dns|lowercase',
        ]);
        
        $user = User::where('email', $request->email)->first();

        return $user ? $user->email : '';
    }

    public function sendPassword(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
            
        return redirect()->intended();
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $user = User::create($request->validated());

        Auth::login($user);

        if ($user->role === config('constant.user_roles.partner')) {
            event(new NewPartnerRegistered($user));
        }

        return $this->authService->redirectToRoleBasedPage($user->role);
    }

    public function destroy(Request $request): RedirectResponse
    {
        if ($request->user()->role === config('constant.user_roles.partner')) {
            return redirect()->route('partner-login.create');
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');   
    }
}