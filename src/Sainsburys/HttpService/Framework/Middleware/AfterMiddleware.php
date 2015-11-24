<?php
namespace Sainsburys\HttpService\Framework\Middleware;

use Psr\Http\Message\ResponseInterface;

interface AfterMiddleware extends Middleware
{
    /**
     * @param ResponseInterface $originalResponse
     * @return ResponseInterface
     */
    public function apply(ResponseInterface $originalResponse);
}
