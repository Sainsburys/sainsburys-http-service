<?php
namespace Sainsburys\HttpService\Framework\Middleware;

interface BeforeMiddleware extends Middleware
{
    /**
     * @param RequestAndResponse $originalRequestAndResponse
     * @return RequestAndResponse
     */
    public function apply(RequestAndResponse $originalRequestAndResponse);
}
