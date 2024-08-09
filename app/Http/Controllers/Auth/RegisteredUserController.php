<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisteredUserController extends Controller
{
    // In case you require a new page for registration
    public function create()
    {
        // 
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $user = User::create($request->validated());

        $user->assignRole(config('constant.role.customer'));

        Auth::login($user);

        return redirect()->intended(route('home', absolute: false));
    }
}