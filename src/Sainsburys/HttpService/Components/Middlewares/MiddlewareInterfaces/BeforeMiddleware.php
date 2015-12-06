<?php
namespace Sainsburys\HttpService\Components\Middlewares\MiddlewareInterfaces;

use Sainsburys\HttpService\Components\Middlewares\Http\RequestAndResponse;

interface BeforeMiddleware extends Middleware
{
    public function apply(RequestAndResponse $originalRequestAndResponse): RequestAndResponse;
}
