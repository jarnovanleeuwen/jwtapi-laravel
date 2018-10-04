<?php

namespace JwtApi\Laravel\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use JwtApi\Laravel\Request as ApiRequest;

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

        $client = Auth::guard()->client();

        ApiRequest::create([
            'api_client_id' => $client ? $client->id : null,
            'request' => (string) $request,
            'response' => (string) $response
        ]);

        return $response;
    }
}
