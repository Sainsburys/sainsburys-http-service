<?php
namespace Sainsburys\HttpService\Components\Middlewares\MiddlewareInterfaces;

use Psr\Http\Message\ResponseInterface;

interface AfterMiddleware extends Middleware
{
    public function apply(ResponseInterface $originalResponse): ResponseInterface;
}
