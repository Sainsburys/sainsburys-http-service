<?php
namespace Sainsburys\HttpService\Components\Middlewares\MiddlewareInterfaces;

use Sainsburys\HttpService\Components\Middlewares\Http\RequestAndResponse;

interface BeforeMiddleware extends Middleware
{
    /**
     * @param RequestAndResponse $originalRequestAndResponse
     * @return RequestAndResponse
     */
    public function apply(RequestAndResponse $originalRequestAndResponse);
}
