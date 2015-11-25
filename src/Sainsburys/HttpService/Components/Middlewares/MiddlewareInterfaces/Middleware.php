<?php
namespace Sainsburys\HttpService\Components\Middlewares\MiddlewareInterfaces;

interface Middleware
{
    /**
     * @return string
     */
    public function getName();
}
