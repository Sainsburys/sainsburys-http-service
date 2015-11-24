<?php
namespace SainsburysSpec\Sainsburys\HttpService\Framework\Middleware\Manager;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Sainsburys\HttpService\Framework\Middleware\BeforeMiddleware;
use Sainsburys\HttpService\Framework\Middleware\AfterMiddleware;

class MiddlewareManagerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\Sainsburys\HttpService\Framework\Middleware\Manager\MiddlewareManager');
    }

    function it_can_apply_before_middlewares(
        RequestInterface  $originalRequest,
        ResponseInterface $originalResponse,
        BeforeMiddleware  $beforeMiddleware,
        ResponseInterface $finalRequest,
        ResponseInterface $finalResponse
    ) {
        // ARRANGE

        $beforeMiddleware
            ->apply($originalRequest, $originalResponse)
            ->willReturn([$finalRequest, $finalResponse]);

        $this->clearBeforeMiddlewareList();
        $this->addToStartOfBeforeMiddlewareList($beforeMiddleware);

        // ACT
        $result = $this->applyBeforeMiddlewares($originalRequest, $originalResponse);

        // ASSERT
        $result->shouldBe([$finalRequest, $finalResponse]);
    }

    function it_can_apply_multiple_before_middlewares_in_the_right_order(
        RequestInterface  $originalRequest,
        ResponseInterface $originalResponse,
        BeforeMiddleware  $beforeMiddleware1,
        RequestInterface  $requestAfterMiddleware1,
        ResponseInterface $responseAfterMiddleware1,
        BeforeMiddleware  $beforeMiddleware2,
        ResponseInterface $finalRequest,
        ResponseInterface $finalResponse
    ) {
        // ARRANGE

        $beforeMiddleware1
            ->apply($originalRequest, $originalResponse)
            ->willReturn([$requestAfterMiddleware1, $responseAfterMiddleware1]);

        $beforeMiddleware2
            ->apply($requestAfterMiddleware1, $responseAfterMiddleware1)
            ->willReturn([$finalRequest, $finalResponse]);

        $this->clearBeforeMiddlewareList();
        $this->addToStartOfBeforeMiddlewareList($beforeMiddleware1);
        $this->addToEndOfBeforeMiddlewareList($beforeMiddleware2);

        // ACT
        $result = $this->applyBeforeMiddlewares($originalRequest, $originalResponse);

        // ASSERT
        $result->shouldBe([$finalRequest, $finalResponse]);
    }

    function it_can_apply_multiple_after_middlewares_in_the_right_order(
        RequestInterface  $originalRequest,
        ResponseInterface $originalResponse,
        AfterMiddleware   $afterMiddleware1,
        RequestInterface  $requestAfterMiddleware1,
        ResponseInterface $responseAfterMiddleware1,
        AfterMiddleware   $afterMiddleware2,
        ResponseInterface $finalRequest,
        ResponseInterface $finalResponse
    ) {
        // ARRANGE

        $afterMiddleware1
            ->apply($originalRequest, $originalResponse)
            ->willReturn([$requestAfterMiddleware1, $responseAfterMiddleware1]);

        $afterMiddleware2
            ->apply($requestAfterMiddleware1, $responseAfterMiddleware1)
            ->willReturn([$finalRequest, $finalResponse]);

        $this->clearAfterMiddlewareList();
        $this->addToStartOfAfterMiddlewareList($afterMiddleware1);
        $this->addToEndOfAfterMiddlewareList($afterMiddleware2);

        // ACT
        $result = $this->applyAfterMiddlewares($originalRequest, $originalResponse);

        // ASSERT
        $result->shouldBe([$finalRequest, $finalResponse]);
    }
}
