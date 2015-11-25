<?php
namespace Sainsburys\HttpService\Components\Middlewares\MiddlewareInterfaces;

use Psr\Http\Message\ResponseInterface;

interface AfterMiddleware extends Middleware
{
    /**
     * @param ResponseInterface $originalResponse
     * @return ResponseInterface
     */
    public function apply(ResponseInterface $originalResponse);
}
