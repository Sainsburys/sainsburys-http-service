<?php
namespace Sainsburys\HttpService\Framework\Middleware\Exception;

use Sainsburys\HttpService\Framework\Exception\ExceptionWithHttpStatus;
use Sainsburys\HttpService\Framework\Middleware\Middleware;
use Teapot\StatusCode\Http;

class MiddlewareReturnTypeException extends \RuntimeException implements ExceptionWithHttpStatus
{
    /**
     * @param Middleware $middleware
     * @return MiddlewareReturnTypeException
     */
    public static function constructFromMiddleware(Middleware $middleware)
    {
        $message = "Middleware named '" . $middleware->getName() . "' didn't return Sainsburys\\HttpService\\Framework\\Middleware\\RequestAndResponse";
        return new static($message);
    }

    /**
     * @return int
     */
    public function getHttpStatusCode()
    {
        return Http::INTERNAL_SERVER_ERROR; //500
    }
}
