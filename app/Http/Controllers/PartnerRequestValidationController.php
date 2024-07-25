<?php

namespace App\Http\Controllers;

use App\Http\Requests\PartnerFormRequest;
use Illuminate\Http\Request;

class PartnerRequestValidationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(PartnerFormRequest $request)
    {
        return redirect(route('partner-account.create', absolute: false))->with([
            'partner' => $request->validated(),
        ]);   
    }   
}
