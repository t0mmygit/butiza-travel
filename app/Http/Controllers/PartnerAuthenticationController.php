<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\PartnerLoginRequest;
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
            'status' => auth()->user(),
        ]);
    }

    public function store(PartnerLoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->route('partner-account');
    }
}