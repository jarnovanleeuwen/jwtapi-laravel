<?php
namespace JwtApi\Laravel\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use JwtApi\Server\Exceptions\ServerException;

class AuthenticationException extends ServerException
{
    const CLIENT_NOT_FOUND = 201;
    const USER_NOT_FOUND = 202;
    
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
            'code' => $this->getCode()
        ], 400);
    }

    public static function inherit(Exception $exception): self
    {
        return new static($exception->getMessage(), $exception->getCode());
    }

    public static function clientNotFound(string $message = "Client not found"): self
    {
        return new static($message, static::CLIENT_NOT_FOUND);
    }

    public static function userNotFound(string $message = "User not found"): self
    {
        return new static($message, static::USER_NOT_FOUND);
    }
}
