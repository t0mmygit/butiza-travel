<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class PartnerAuthenticationController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Partner/Account/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate(config('constant.user_roles.partner'));

        $request->session()->regenerate();

        return redirect()->route('partner-account');
    }
}