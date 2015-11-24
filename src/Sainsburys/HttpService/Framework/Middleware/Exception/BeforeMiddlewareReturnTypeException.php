<?php
namespace Sainsburys\HttpService\Framework\Middleware\Exception;

use Sainsburys\HttpService\Framework\Exception\ExceptionWithHttpStatus;
use Sainsburys\HttpService\Framework\Middleware\BeforeMiddleware;
use Teapot\StatusCode\Http;

class BeforeMiddlewareReturnTypeException extends \RuntimeException implements ExceptionWithHttpStatus
{
    /**
     * @param BeforeMiddleware $middleware
     * @return BeforeMiddlewareReturnTypeException
     */
    public static function constructFromMiddleware(BeforeMiddleware $middleware)
    {
        $message = "BeforeMiddleware named '" . $middleware->getName() . "' didn't return Sainsburys\\HttpService\\Framework\\Middleware\\RequestAndResponse";
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
