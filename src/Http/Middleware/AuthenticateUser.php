<?php

namespace JwtApi\Laravel\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use JwtApi\Laravel\Exceptions\ApiException;
use JwtApi\Laravel\Exceptions\AuthenticationException;
use JwtApi\Server\Exceptions\ServerException;

class AuthenticateUser
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
        $auth = Auth::guard();

        try {
            $user = $auth->user();
            $client = $auth->client();

            if (!$user || !$user->clients->contains($client->id)) {
                throw AuthenticationException::userNotFound();
            }
        } catch (ServerException $exception) {
            throw ApiException::inherit($exception);
        }

        return $next($request);
    }
}
