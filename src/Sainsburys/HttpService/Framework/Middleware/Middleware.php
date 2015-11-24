<?php
namespace Sainsburys\HttpService\Framework\Middleware;

interface Middleware
{
    /**
     * @return string
     */
    public function getName();
}
