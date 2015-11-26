<?php
namespace Sainsburys\HttpService\Components\Middlewares\Exception;

use Sainsburys\HttpService\Components\ErrorHandling\Exceptions\ExceptionWithHttpStatus;
use Sainsburys\HttpService\Components\Middlewares\MiddlewareInterfaces\AfterMiddleware;
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
