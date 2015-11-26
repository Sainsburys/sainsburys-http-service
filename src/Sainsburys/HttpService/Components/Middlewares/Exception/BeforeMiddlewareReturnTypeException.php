<?php
namespace Sainsburys\HttpService\Components\Middlewares\Exception;

use Sainsburys\HttpService\Components\ErrorHandling\Exceptions\ExceptionWithHttpStatus;
use Sainsburys\HttpService\Components\Middlewares\MiddlewareInterfaces\BeforeMiddleware;
use Teapot\StatusCode\Http;

class BeforeMiddlewareReturnTypeException extends \RuntimeException implements ExceptionWithHttpStatus
{
    /**
     * @param BeforeMiddleware $middleware
     * @return BeforeMiddlewareReturnTypeException
     */
    public static function constructFromMiddleware(BeforeMiddleware $middleware)
    {
        $message = "BeforeMiddleware named '" . $middleware->getName() . "' didn't return Sainsburys\\HttpService\\Components\\Middlewares\\Http\\RequestAndResponse";
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
