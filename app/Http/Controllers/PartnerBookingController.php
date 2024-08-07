<?php

namespace App\Http\Controllers;

use App\Http\Requests\PartnerBookingRequest;
use App\Services\PartnerBookingService;

class PartnerBookingController extends Controller
{
    public function __construct(
        protected PartnerBookingService $partnerBookingService
    ) {}

    public function store(PartnerBookingRequest $request)
    {
        $paymentId = $this->partnerBookingService->store($request);

        return redirect(route('payment.show', ['id' => $paymentId], absolute: true));
    }
}