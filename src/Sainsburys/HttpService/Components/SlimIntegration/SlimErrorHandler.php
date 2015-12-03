<?php
namespace Sainsburys\HttpService\Components\SlimIntegration;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class SlimErrorHandler
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param \Exception             $exception
     * @throws \Exception
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, \Exception $exception)
    {
        throw $exception;
    }
}
