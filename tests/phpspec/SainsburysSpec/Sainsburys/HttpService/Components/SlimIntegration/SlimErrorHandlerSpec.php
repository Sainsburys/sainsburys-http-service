<?php

namespace SainsburysSpec\Sainsburys\HttpService\Components\SlimIntegration;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class SlimErrorHandlerSpec extends ObjectBehavior
{
    function it_is_initialisable()
    {
        $this->shouldHaveType('\Sainsburys\HttpService\Components\SlimIntegration\SlimErrorHandler');
    }

    function it_rethrows_exceptions(ServerRequestInterface $request, ResponseInterface $response, \Exception $exception)
    {
        $this
            ->shouldThrow('\Exception')
            ->during('__invoke', [$request, $response, $exception]);
    }
}
