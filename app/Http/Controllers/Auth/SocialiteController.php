<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse as HttpRedirectResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SocialiteController extends Controller
{
    public function githubRedirect(): RedirectResponse
    {
        return Socialite::driver('github')->redirect();
    }

    public function githubCallback(): HttpRedirectResponse
    {
        $user = Socialite::driver('github')->user();

        $user = User::updateOrCreate([
            'email' => $user->getEmail(),
        ], [
            # TODO: Handle token expiration
            # https://laravel.com/docs/11.x/socialite#retrieving-user-details
            'first_name' => $user->getNickname(),
            'last_name' => $user->getNickname(),
            'email' => $user->getEmail(),
            'avatar' => $user->getAvatar(),
            'is_social_auth' => true,
        ]);

        Auth::login($user);

        return redirect(route('home', absolute: false));
    }

    public function redirect(string $provider): RedirectResponse
    {
        return Socialite::driver($provider)->redirect();
    }
}
