<?php
namespace Sainsburys\HttpService\Framework\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\MessageInterface;

interface Middleware
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     *
     * @return MessageInterface[]  [0 => RequestInterface, 1 => ResponseInterface]
     */
    public function apply(RequestInterface $request, ResponseInterface $response);
}
