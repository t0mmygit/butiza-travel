<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class PartnerLoginRequest extends LoginRequest
{
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! $this->attemptAuthenticatedWhenUserIsPartner()) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    private function attemptAuthenticatedWhenUserIsPartner(): bool
    {
        return Auth::attemptWhen(
                credentials: $this->only('email', 'password'),
                callbacks: fn (User $user) => $this->isPartner($user),
                remember: $this->boolean('remember')
        );
    }

    private function isPartner(User $user): bool
    {
        return $user->hasRole(config('constant.role.partner'));
    }
}
