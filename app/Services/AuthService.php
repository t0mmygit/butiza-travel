<?php

namespace App\Services;

use Illuminate\Http\RedirectResponse;

class AuthService
{
    public function redirectToRoleBasedPage(string $role): RedirectResponse
    {
        return $role === config('constant.user_roles.partner')
            ? redirect(route('partner-login.create', absolute: false))
            : redirect(route('home', absolute: false));   
    }
}