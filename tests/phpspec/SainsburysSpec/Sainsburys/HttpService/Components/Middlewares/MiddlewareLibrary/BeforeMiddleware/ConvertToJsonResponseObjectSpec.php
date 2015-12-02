<?php
namespace SainsburysSpec\Sainsburys\HttpService\Components\Middlewares\MiddlewareLibrary\BeforeMiddleware;

use Sainsburys\HttpService\Components\Middlewares\MiddlewareLibrary\BeforeMiddleware\ConvertToJsonResponseObject;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sainsburys\HttpService\Components\Middlewares\Http\RequestAndResponse;
use Zend\Diactoros\Response\JsonResponse;

/**
 * @mixin ConvertToJsonResponseObject
 */
class ConvertToJsonResponseObjectSpec extends ObjectBehavior
{
    function it_is_initialisable()
    {
        $this->shouldHaveType('Sainsburys\HttpService\Components\Middlewares\MiddlewareLibrary\BeforeMiddleware\ConvertToJsonResponseObject');
    }

    function it_is_a_valid_middleware()
    {
        $this->shouldHaveType('Sainsburys\HttpService\Components\Middlewares\MiddlewareInterfaces\Middleware');
        $this->shouldHaveType('Sainsburys\HttpService\Components\Middlewares\MiddlewareInterfaces\BeforeMiddleware');
    }

    function it_has_a_name()
    {
        $this->getName()->shouldBe('convert-to-json-response-object');
    }

    function it_can_convert_the_response_object_type(
        RequestAndResponse     $requestAndResponse,
        ServerRequestInterface $request,
        ResponseInterface      $response
    ) {
        // ARRANGE
        $requestAndResponse->request()->willReturn($request);
        $requestAndResponse->response()->willReturn($response);

        $response->getHeaders()->willReturn(['header-name' => 'header-value']);
        $response->getStatusCode()->willReturn(201);

        // ACT
        $resultingRequestAndResponse = $this->apply($requestAndResponse);

        // ASSERT
        $resultingRequestAndResponse->request()->shouldBeLike($request);

        $expectedResponseHeaders    = ['header-name' => ['header-value'], 'Content-Type' => ['application/json']];
        $expectedResponseStatusCode = 201;

        $resultingRequestAndResponse->response()->getStatusCode()->shouldBe($expectedResponseStatusCode);
        $resultingRequestAndResponse->response()->getHeaders()->shouldBe($expectedResponseHeaders);
    }
}
