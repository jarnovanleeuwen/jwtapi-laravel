<?php
namespace JwtApi\Laravel\Exceptions;

use Exception;

class AuthenticationException extends ApiException
{
    const CLIENT_NOT_FOUND = 201;
    const USER_NOT_FOUND = 202;
    
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function clientNotFound(string $message = "Client not found"): self
    {
        return new static($message, static::CLIENT_NOT_FOUND);
    }

    public static function userNotFound(string $message = "User not found or not accessible by this API Client"): self
    {
        return new static($message, static::USER_NOT_FOUND);
    }
}
