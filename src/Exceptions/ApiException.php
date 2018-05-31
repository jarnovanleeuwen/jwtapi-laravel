<?php
namespace JwtApi\Laravel\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use JwtApi\Server\Exceptions\ServerException;

class ApiException extends ServerException
{
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
}
