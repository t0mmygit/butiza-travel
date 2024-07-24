<?php

namespace App\Http\Controllers;

use App\Events\NewPartnerRegistered;
use App\Http\Requests\PartnerRegisterRequest;
use App\Models\Partner;
use App\Models\User;
use App\Services\PartnerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class PartnerController extends Controller
{
    public function __construct(
        public PartnerService $partnerService
    ) {}

    public function index(): Response
    {
        $partner = User::with('notifications')->where('id', Auth::id())->first();

        return Inertia::render('Partner/Account/Index', [
            'partner' => $partner,
            'notifications' => $partner->notifications,
        ]);
    }

    public function store(PartnerRegisterRequest $request): RedirectResponse
    {
        $user = User::create($request->validated());

        Auth::login($user);

        event(new NewPartnerRegistered($user));

        return redirect()->route('partner-account');
    }

    public function create(Request $request): RedirectResponse | Response
    {
        return Inertia::render('Partner/Account/Register', [
            'partner' => $this->partnerService->partner($request),
        ]);
    }
}