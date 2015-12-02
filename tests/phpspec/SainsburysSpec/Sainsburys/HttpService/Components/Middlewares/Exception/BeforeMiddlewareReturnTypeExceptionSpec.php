<?php

namespace SainsburysSpec\Sainsburys\HttpService\Components\Middlewares\Exception;

use Sainsburys\HttpService\Components\Middlewares\Exception\BeforeMiddlewareReturnTypeException;
use Sainsburys\HttpService\Components\Middlewares\MiddlewareInterfaces\BeforeMiddleware;
use Teapot\StatusCode\Http;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin BeforeMiddlewareReturnTypeException
 */
class BeforeMiddlewareReturnTypeExceptionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sainsburys\HttpService\Components\Middlewares\Exception\BeforeMiddlewareReturnTypeException');
    }

    function it_has_a_helpful_status_code()
    {
        $this->getHttpStatusCode()->shouldBe(Http::INTERNAL_SERVER_ERROR);
        $this->getHttpStatusCode()->shouldBe(500);
    }

    function it_can_be_constructed_from_a_middleware(BeforeMiddleware $beforeMiddleware)
    {
        $this->beConstructedThrough('constructFromMiddleware', [$beforeMiddleware]);
        $beforeMiddleware->getName()->willReturn('middleware-name');
        $this->getMessage()->shouldBe('BeforeMiddleware named \'middleware-name\' didn\'t return Sainsburys\HttpService\Components\Middlewares\Http\RequestAndResponse');
    }
}
