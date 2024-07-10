<?php

namespace App\Http\Controllers;

use App\Http\Requests\PartnerRequest;
use App\Services\PartnerRequestService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PartnerRequestController extends Controller
{
    protected $partnerRequestService;

    public function __construct(PartnerRequestService $partnerRequestService)
    {
        $this->partnerRequestService = $partnerRequestService;
    }

    public function create(): Response
    {
        return Inertia::render('Partner/Index');
    }

    public function store(PartnerRequest $request): RedirectResponse
    {
        $partner = $this->partnerRequestService->create($request);

        $parameter = $this->partnerRequestService->encryptUUID($partner->reference_code);

        return redirect()->route('partner-account.create', $parameter);
    }
}