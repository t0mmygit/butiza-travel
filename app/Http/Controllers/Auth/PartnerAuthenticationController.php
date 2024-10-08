<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PartnerLoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class PartnerAuthenticationController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Partner/Account/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => Auth::check(),
        ]);
    }

    public function store(PartnerLoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->route('partner-account');
    }
}