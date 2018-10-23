<?php

namespace JwtApi\Laravel\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use JwtApi\Laravel\Request as ApiRequest;
use JwtApi\Server\Exceptions\ServerException;

class LogRequest
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $client = null;

        try {
            $client = Auth::guard()->client();
        } catch (ServerException $exception) {
            //
        }

        ApiRequest::create([
            'api_client_id' => $client ? $client->id : null,
            'request' => (string) $request,
            'response' => (string) $response
        ]);

        return $response;
    }
}
