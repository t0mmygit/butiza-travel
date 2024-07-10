<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests;
use Symfony\Component\HttpFoundation\Response;

class AuthenticatePartner implements AuthenticatesRequests
{
    protected $auth;

    protected static $redirectToCallback;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$guards): Response
    {
        $this->authenticate($request, $guards);

        return $next($request);
    }

    // if authenticated, user will be redirected to the partner account page
    protected function authenticate($request, array $guards)
    {
        if (empty($guards)) {
            $guards = [null];
        }

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                return $this->auth->shouldUse($guard);            
            }
        };

        $this->unauthenticated($request, $guards);
    }

    protected function unauthenticated($request, array $guards)
    {
        throw new AuthenticationException(
            'Unauthenticated.',
            $guards,
            $request->expectsJson() ? null : $this->redirectTo($request),
        );
    }

    protected function redirectTo($request)
    {
        if (static::$redirectToCallback) {
            return call_user_func(static::$redirectToCallback, $request);
        }

        return redirect()->route('partner-account.create');
    }

    public static function redirectUsing(callable $redirectToCallback) 
    {
        static::$redirectToCallback = $redirectToCallback;
    }
}