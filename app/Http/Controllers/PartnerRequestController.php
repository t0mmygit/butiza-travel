<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PartnerRequestController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Partner/Index');
    }

    public function store(Request $request): RedirectResponse
    {
        $partner = new Partner;
        $partner->fill($request->all());

        $partner->user()->associate(auth()->user());
        $partner->save();

        return redirect(route('partner-account', absolute: false));
    }
}