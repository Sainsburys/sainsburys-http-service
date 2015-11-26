<?php
namespace SainsburysSpec\Sainsburys\HttpService\Components\Middlewares;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use Sainsburys\HttpService\Components\Middlewares\MiddlewareInterfaces\BeforeMiddleware;
use Sainsburys\HttpService\Components\Middlewares\MiddlewareInterfaces\AfterMiddleware;
use Sainsburys\HttpService\Components\Middlewares\Http\RequestAndResponse;
use Sainsburys\HttpService\Components\Middlewares\MiddlewareManager;

/**
 * @mixin MiddlewareManager
 */
class MiddlewareManagerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\Sainsburys\HttpService\Components\Middlewares\MiddlewareManager');
    }

    function it_can_apply_before_middlewares(
        RequestAndResponse $originalRequestAndResponse,
        BeforeMiddleware   $beforeMiddleware,
        RequestAndResponse $finalRequestAndResponse
    ) {
        // ARRANGE
        $beforeMiddleware
            ->apply($originalRequestAndResponse)
            ->willReturn($finalRequestAndResponse);

        $this->clearBeforeMiddlewareList();
        $this->addToStartOfBeforeMiddlewareList($beforeMiddleware);

        // ACT
        $result = $this->applyBeforeMiddlewares($originalRequestAndResponse);

        // ASSERT
        $result->shouldBe($finalRequestAndResponse);
    }

    function it_can_apply_multiple_before_middlewares_in_the_right_order(
        RequestAndResponse $originalRequestAndResponse,
        BeforeMiddleware   $beforeMiddleware1,
        RequestAndResponse $requestAndResponseAfterMiddleware1,
        BeforeMiddleware   $beforeMiddleware2,
        RequestAndResponse $finalRequestAndResponse
    ) {
        // ARRANGE

        $beforeMiddleware1
            ->apply($originalRequestAndResponse)
            ->willReturn($requestAndResponseAfterMiddleware1);

        $beforeMiddleware2
            ->apply($requestAndResponseAfterMiddleware1)
            ->willReturn($finalRequestAndResponse);

        $this->clearBeforeMiddlewareList();
        $this->addToStartOfBeforeMiddlewareList($beforeMiddleware1);
        $this->addToEndOfBeforeMiddlewareList($beforeMiddleware2);

        // ACT
        $result = $this->applyBeforeMiddlewares($originalRequestAndResponse);

        // ASSERT
        $result->shouldBe($finalRequestAndResponse);
    }

    function it_can_apply_multiple_after_middlewares_in_the_right_order(
        ResponseInterface $originalResponse,
        AfterMiddleware   $middleware1,
        ResponseInterface $responseAfterMiddleware1,
        AfterMiddleware   $middleware2,
        ResponseInterface $finalResponse
    ) {
        // ARRANGE

        $middleware1
            ->apply($originalResponse)
            ->willReturn($responseAfterMiddleware1);

        $middleware2
            ->apply($responseAfterMiddleware1)
            ->willReturn($finalResponse);

        $this->clearAfterMiddlewareList();
        $this->addToEndOfAfterMiddlewareList($middleware1);
        $this->addToEndOfAfterMiddlewareList($middleware2);

        // ACT
        $result = $this->applyAfterMiddlewares($originalResponse);

        // ASSERT
        $result->shouldBe($finalResponse);
    }

    function it_can_validate_middleware_responses(
        RequestAndResponse $originalRequestAndResponse,
        \stdClass          $resultOfMiddleware,
        BeforeMiddleware   $beforeMiddleware
    ) {
        $beforeMiddleware->getName()->willReturn('middleware-name');
        $beforeMiddleware->apply($originalRequestAndResponse)->willReturn($resultOfMiddleware);

        $this->addToStartOfBeforeMiddlewareList($beforeMiddleware);

        $this
            ->shouldThrow('\Sainsburys\HttpService\Components\Middlewares\Exception\BeforeMiddlewareReturnTypeException')
            ->during('applyBeforeMiddlewares', [$originalRequestAndResponse]);
    }
}
