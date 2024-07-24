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
    public function __construct(
        protected AuthService $authService
    ) {}

    public function sendPassword(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
            
        return redirect()->intended(route('home', absolute: false));
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $user = User::create($request->validated());

        Auth::login($user);

        return redirect()->intended(route('home', absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $this->authService->redirectToRoleBasedPage($request->user()->role);
    }
}