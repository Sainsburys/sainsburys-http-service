<?php
namespace Sainsburys\HttpService\Framework\Middleware;

interface Middleware
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param RequestAndResponse $originalRequestAndResponse
     * @return RequestAndResponse
     */
    public function apply(RequestAndResponse $originalRequestAndResponse);
}
