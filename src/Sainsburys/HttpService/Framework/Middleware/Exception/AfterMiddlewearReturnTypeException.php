<?php
namespace Sainsburys\HttpService\Framework\Middleware\Exception;

use Sainsburys\HttpService\Framework\Exception\ExceptionWithHttpStatus;
use Sainsburys\HttpService\Framework\Middleware\AfterMiddleware;
use Teapot\StatusCode\Http;

class AfterMiddlewareReturnTypeException extends \RuntimeException implements ExceptionWithHttpStatus
{
    /**
     * @param AfterMiddleware $middleware
     * @return AfterMiddlewareReturnTypeException
     */
    public static function constructFromMiddleware(AfterMiddleware $middleware)
    {
        $message = "AfterMiddleware named '" . $middleware->getName() . "' didn't return Psr\\Http\\Message\\ResponseInterface";
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
