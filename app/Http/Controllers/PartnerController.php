<?php

namespace App\Http\Controllers;

use App\Events\NewPartnerRegistered;
use App\Http\Requests\PartnerCompanyUpdateRequest;
use App\Http\Requests\PartnerRegisterRequest;
use App\Models\ContactMethod;
use App\Models\Partner;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class PartnerController extends Controller
{
    public function index(): Response
    {
        $user = User::with([
            'bookings.package.tour',
            'notifications',
            'partner.discount',
            'partner.settings.contactMethod',
        ])->where('id', Auth::id())->first();

        return Inertia::render('Partner/Account/Index', [
            'user' => $user,
            'notifications' => $user->notifications,
            'tours' => Tour::with('packages.activities')->get(),
            'contactMethods' => ContactMethod::all(),
        ]);
    }

    public function store(PartnerRegisterRequest $request): void
    {
        $user = User::create($request->only([
            'first_name', 'last_name', 'email', 'phone_number', 'password', 'role'
        ]));

        Auth::login($user);

        event(new NewPartnerRegistered($user));

        // redirection occur in '/Partner/Account/Register.vue' on success.
    }

    public function create(Request $request): Response
    {
        // TODO: The 'checks' in service should be handled with middleware, Partner Service will no longer be necessary.
        return Inertia::render('Partner/Account/Register', [
            'partner' => $request->session()->get('partner'),
        ]);
    }

    public function update(PartnerCompanyUpdateRequest $request, Partner $partner)
    {
        $this->authorize('update', $partner);
        
        $partner->update($request->validated());

        // event for notification

        return back();
    }
}