<?php

namespace SainsburysSpec\Sainsburys\HttpService\Components\Middlewares\Exception;

use Sainsburys\HttpService\Components\Middlewares\Exception\AfterMiddlewareReturnTypeException;
use Sainsburys\HttpService\Components\Middlewares\MiddlewareInterfaces\AfterMiddleware;
use Teapot\StatusCode\Http;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin AfterMiddlewareReturnTypeException
 */
class AfterMiddlewareReturnTypeExceptionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sainsburys\HttpService\Components\Middlewares\Exception\AfterMiddlewareReturnTypeException');
    }

    function it_has_a_helpful_status_code()
    {
        $this->getHttpStatusCode()->shouldBe(Http::INTERNAL_SERVER_ERROR);
        $this->getHttpStatusCode()->shouldBe(500);
    }

    function it_can_be_constructed_from_a_middleware(AfterMiddleware $afterMiddleware)
    {
        $this->beConstructedThrough('constructFromMiddleware', [$afterMiddleware]);
        $afterMiddleware->getName()->willReturn('middleware-name');
        $this->getMessage()->shouldBe('AfterMiddleware named \'middleware-name\' didn\'t return Psr\\Http\\Message\\ResponseInterface');
    }
}
