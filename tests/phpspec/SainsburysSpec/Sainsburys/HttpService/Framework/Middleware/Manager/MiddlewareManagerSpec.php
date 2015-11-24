<?php
namespace SainsburysSpec\Sainsburys\HttpService\Framework\Middleware\Manager;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sainsburys\HttpService\Framework\Middleware\BeforeMiddleware;
use Sainsburys\HttpService\Framework\Middleware\AfterMiddleware;
use Sainsburys\HttpService\Framework\Middleware\RequestAndResponse;

class MiddlewareManagerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\Sainsburys\HttpService\Framework\Middleware\Manager\MiddlewareManager');
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
        RequestAndResponse $originalRequestAndResponse,
        AfterMiddleware    $middleware1,
        RequestAndResponse $requestAndResponseAfterMiddleware1,
        AfterMiddleware    $middleware2,
        RequestAndResponse $finalRequestAndResponse
    ) {
        // ARRANGE

        $middleware1
            ->apply($originalRequestAndResponse)
            ->willReturn($requestAndResponseAfterMiddleware1);

        $middleware2
            ->apply($requestAndResponseAfterMiddleware1)
            ->willReturn($finalRequestAndResponse);

        $this->clearAfterMiddlewareList();
        $this->addToEndOfAfterMiddlewareList($middleware1);
        $this->addToEndOfAfterMiddlewareList($middleware2);

        // ACT
        $result = $this->applyAfterMiddlewares($originalRequestAndResponse);

        // ASSERT
        $result->shouldBe($finalRequestAndResponse);
    }

    function it_can_validate_middleware_responses(
        RequestAndResponse $originalRequestAndResponse,
        \stdClass          $resultOfMiddleware,
        BeforeMiddleware   $beforeMiddleware
    ) {
        $beforeMiddleware
            ->getName()
            ->willReturn('middleware-name');

        $beforeMiddleware
            ->apply($originalRequestAndResponse)
            ->willReturn($resultOfMiddleware);

        $this->addToStartOfBeforeMiddlewareList($beforeMiddleware);

        $this
            ->shouldThrow('Sainsburys\HttpService\Framework\Middleware\Exception\MiddlewareReturnTypeException')
            ->during('applyBeforeMiddlewares', [$originalRequestAndResponse]);
    }
}
