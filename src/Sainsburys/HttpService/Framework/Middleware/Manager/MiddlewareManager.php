<?php
namespace Sainsburys\HttpService\Framework\Middleware\Manager;

use Sainsburys\HttpService\Framework\Middleware\BeforeMiddleware;
use Sainsburys\HttpService\Framework\Middleware\AfterMiddleware;
use Sainsburys\HttpService\Framework\Middleware\Exception\MiddlewareReturnTypeException;
use Sainsburys\HttpService\Framework\Middleware\Middleware;
use Psr\Http\Message\MessageInterface;
use Sainsburys\HttpService\Framework\Middleware\RequestAndResponse;

class MiddlewareManager
{
    /** @var BeforeMiddleware[] */
    private $beforeMiddlewareList = [];

    /** @var AfterMiddleware[] */
    private $afterMiddlewareList = [];

    public function clearBeforeMiddlewareList()
    {
        $this->beforeMiddlewareList = [];
    }

    /**
     * @param BeforeMiddleware $beforeMiddleware
     */
    public function addToStartOfBeforeMiddlewareList(BeforeMiddleware $beforeMiddleware)
    {
        array_unshift($this->beforeMiddlewareList, $beforeMiddleware);
    }

    /**
     * @param BeforeMiddleware $beforeMiddleware
     */
    public function addToEndOfBeforeMiddlewareList(BeforeMiddleware $beforeMiddleware)
    {
        $this->beforeMiddlewareList[] = $beforeMiddleware;
    }

    public function clearAfterMiddlewareList()
    {
        $this->afterMiddlewareList = [];
    }

    /**
     * @param AfterMiddleware $afterMiddleware
     */
    public function addToStartOfAfterMiddlewareList(AfterMiddleware $afterMiddleware)
    {
        array_unshift($this->afterMiddlewareList, $afterMiddleware);
    }

    /**
     * @param AfterMiddleware $afterMiddleware
     */
    public function addToEndOfAfterMiddlewareList(AfterMiddleware $afterMiddleware)
    {
        $this->afterMiddlewareList[] = $afterMiddleware;
    }

    /**
     * @param RequestAndResponse $requestAndResponse
     * @return MessageInterface[]
     */
    public function applyBeforeMiddlewares(RequestAndResponse $requestAndResponse)
    {
        return $this->applyMiddlewareList($this->beforeMiddlewareList, $requestAndResponse);
    }

    /**
     * @param RequestAndResponse $requestAndResponse
     * @return MessageInterface[]
     */
    public function applyAfterMiddlewares(RequestAndResponse $requestAndResponse)
    {
        return $this->applyMiddlewareList($this->afterMiddlewareList, $requestAndResponse);
    }

    /**
     * @param Middleware[]       $middlewares
     * @param RequestAndResponse $requestAndResponse
     * @return RequestAndResponse
     */
    private function applyMiddlewareList(array $middlewares, RequestAndResponse $requestAndResponse)
    {
        foreach ($middlewares as $middleware) {
            $requestAndResponse = $this->applyMiddleware($middleware, $requestAndResponse);
        }
        return $requestAndResponse;
    }

    /**
     * @param Middleware         $middleware
     * @param RequestAndResponse $requestAndResponse
     * @return RequestAndResponse
     */
    private function applyMiddleware(Middleware $middleware, RequestAndResponse $requestAndResponse)
    {
        $result = $middleware->apply($requestAndResponse);

        if (! $result instanceof RequestAndResponse) {
            throw MiddlewareReturnTypeException::constructFromMiddleware($middleware);
        }

        return $result;
    }
}
