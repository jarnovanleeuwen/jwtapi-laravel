<?php

namespace JwtApi\Laravel\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use JwtApi\Laravel\Exceptions\AuthenticationException;
use JwtApi\Server\Exceptions\ServerException;

class AuthenticateClient
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     *
     * @throws AuthenticationException
     */
    public function handle(Request $request, Closure $next)
    {
        Auth::shouldUse('api');

        try {
            if (!Auth::guard()->client()) {
                throw AuthenticationException::clientNotFound();
            }
        } catch (ServerException $exception) {
            throw AuthenticationException::inherit($exception);
        }

        return $next($request);
    }
}
