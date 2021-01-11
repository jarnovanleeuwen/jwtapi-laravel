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
     * The maximum length of the logged request and response.
     *
     * @var int
     */
    public $length = 2048;
    
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
            'request' => substr((string) $request, 0, $this->length),
            'response' => substr((string) $response, 0, $this->length),
        ]);

        return $response;
    }
}
