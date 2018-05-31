<?php

namespace JwtApi\Laravel\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use JwtApi\Laravel\Exceptions\ApiException;
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
            $client = Auth::guard()->client();

            if (!$client) {
                throw AuthenticationException::clientNotFound();
            }

            $client->used();
        } catch (ServerException $exception) {
            throw ApiException::inherit($exception);
        }

        return $next($request);
    }
}
