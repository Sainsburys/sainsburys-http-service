<?php
namespace SainsburysSpec\Sainsburys\HttpService\Components\Middlewares\MiddlewareLibrary\BeforeMiddleware;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sainsburys\HttpService\Components\Middlewares\Http\RequestAndResponse;

class CleanRequestAttributesSpec extends ObjectBehavior
{
    function it_is_initialisable()
    {
        $this->shouldHaveType('Sainsburys\HttpService\Components\Middlewares\MiddlewareLibrary\BeforeMiddleware\CleanRequestAttributes');
    }

    function it_is_a_valid_middleware()
    {
        $this->shouldHaveType('Sainsburys\HttpService\Components\Middlewares\MiddlewareInterfaces\Middleware');
        $this->shouldHaveType('Sainsburys\HttpService\Components\Middlewares\MiddlewareInterfaces\BeforeMiddleware');
    }

    function it_has_a_name()
    {
        $this->getName()->shouldBe('clean-slim-stuff-from-request-attributes');
    }

    function it_can_take_some_stuff_out_of_the_request(
        RequestAndResponse     $originalRequestAndResponse,
        ResponseInterface      $originalResponse,
        ServerRequestInterface $originalRequest,
        ServerRequestInterface $requestWithOneAttribRemoved,
        ServerRequestInterface $requestWithBothAttribsRemoved
    ) {
        // ARRANGE
        $originalRequestAndResponse->response()->willReturn($originalResponse);
        $originalRequestAndResponse->request()->willReturn($originalRequest);

        $originalRequest->withoutAttribute('route')->willReturn($requestWithOneAttribRemoved);
        $requestWithOneAttribRemoved->withoutAttribute('route-info')->willReturn($requestWithBothAttribsRemoved);

        // ACT
        $result = $this->apply($originalRequestAndResponse);

        // ASSERT
        $result->request()->shouldBe($requestWithBothAttribsRemoved);
        $result->response()->shouldBe($originalResponse);
    }
}
