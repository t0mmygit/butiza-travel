<?php

namespace App\Http\Controllers;

use App\Http\Requests\PartnerBookingRequest;
use App\Models\Booking;
use App\Services\PartnerBookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class PartnerBookingController extends Controller
{
    public function __construct(
        protected PartnerBookingService $partnerBookingService
    ) {}

    public function store(PartnerBookingRequest $request)
    {
        $parameter = $this->partnerBookingService->store($request->all());

        return redirect(route('payment.show', ['id' => $parameter], absolute: true));
    }
}
