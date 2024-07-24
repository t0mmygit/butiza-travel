<?php

namespace App\Http\Controllers;

use App\Http\Requests\PartnerFormRequest;
use App\Services\PartnerRequestService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PartnerRequestController extends Controller
{
    public function __construct(
        protected PartnerRequestService $partnerRequestService
    ) {}

    public function create(): Response
    {
        return Inertia::render('Partner/Index');
    }

    public function store(PartnerFormRequest $request): RedirectResponse
    {
        $partner = $this->partnerRequestService->create($request);

        $request->session()->put('partner', $partner->toArray());

        return redirect()->route('partner-account.create');
    }
}