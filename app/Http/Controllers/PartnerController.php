<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Inertia\Inertia;
use Inertia\Response;

class PartnerController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Partner/Account/Index', [
            'partner' => User::with('notifications')->where('id', Auth::id())->first(),
        ]);
    }

    public function create(string $query): RedirectResponse | Response
    { 
        if (Auth::check()) {
            return redirect()->route('partner-account');
        }
        
        $uuid = Crypt::decryptString($query);
        $partner = Partner::where('reference_code', $uuid)->first();

        return Inertia::render('Partner/Account/Register', [
            'partner' => $partner,
        ]);
    }
}