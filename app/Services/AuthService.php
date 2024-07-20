<?php

namespace App\Services;

use Illuminate\Http\RedirectResponse;

class AuthService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        
    }

    public function redirectToRoleBasedPage($role): RedirectResponse
    {
        return $role === config('constant.user_roles.partner')
            ? redirect()->route('partner-account')
            : redirect()->intended('home');
    }
}