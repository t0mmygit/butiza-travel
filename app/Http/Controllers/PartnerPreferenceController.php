<?php

namespace App\Http\Controllers;

use App\Http\Requests\PartnerPreferenceUpdateRequest;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerPreferenceController extends Controller
{
    public function update(PartnerPreferenceUpdateRequest $request, Partner $partner)
    {
        // TODO: Authorization in request/here
        $partner->settings()->update($request->validated());
        
        return back();
    }
}