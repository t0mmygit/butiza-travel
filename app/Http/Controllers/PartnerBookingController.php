<?php

namespace App\Http\Controllers;

use App\Http\Requests\PartnerBookingRequest;
use App\Services\PartnerBookingService;
use Illuminate\Http\RedirectResponse;

class PartnerBookingController extends Controller
{
    public function __construct(
        protected PartnerBookingService $partnerBookingService
    ) {}

    public function store(PartnerBookingRequest $request)
    {
        $response = $this->partnerBookingService->store($request);

        return is_array($response)
            ? back()->with(['message' => $response['message'], 'error' => $response['error']])
            : redirect(route('payment.create', ['id' => $response], absolute: false));
    }
}