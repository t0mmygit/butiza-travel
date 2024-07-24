<?php

namespace App\Services;

use App\Models\Partner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\Auth;

class PartnerService
{
    public function partner(Request $request): array | null
    {
        $this->checkPreviousUrl();

        if (Auth::check()) {
            $this->handleAuthenticatedUser($request);
        }

        try {
            return $request->session()->get('partner');
        } catch (\Exception $error) {
            Facades\Log::error($error);
            return null;
        }
    }

    protected function handleAuthenticatedUser(Request $request): RedirectResponse
    {
        if (Auth::user()->role == config('constant.user_roles.partner')) {
            return redirect()->route('partner-account');
        }
        
        // TODO: Warn that user will be logged out.
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    protected function checkPreviousUrl(): void
    {
        if (Facades\URL::previous() !== route('partner.store')) {
            abort(403, 'Direct access is not allowed.');
        }
    }
}