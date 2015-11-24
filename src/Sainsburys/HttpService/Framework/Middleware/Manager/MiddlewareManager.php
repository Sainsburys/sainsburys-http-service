<?php
namespace Sainsburys\HttpService\Framework\Middleware\Manager;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Sainsburys\HttpService\Framework\Middleware\BeforeMiddleware;
use Sainsburys\HttpService\Framework\Middleware\AfterMiddleware;
use Sainsburys\HttpService\Framework\Middleware\Middleware;
use Psr\Http\Message\MessageInterface;

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
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @return MessageInterface[]
     */
    public function applyBeforeMiddlewares(RequestInterface $request, ResponseInterface $response)
    {
        return $this->applyMiddlewareList($this->beforeMiddlewareList, $request, $response);
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return MessageInterface[]
     */
    public function applyAfterMiddlewares(RequestInterface $request, ResponseInterface $response)
    {
        return $this->applyMiddlewareList($this->afterMiddlewareList, $request, $response);
    }

    /**
     * @param Middleware[]      $middlewares
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @return MessageInterface[]
     */
    private function applyMiddlewareList(array $middlewares, RequestInterface $request, ResponseInterface $response)
    {
        foreach ($middlewares as $middleware) {
            list($request, $response) = $this->applyMiddleware($middleware, $request, $response);
        }
        return [$request, $response];
    }

    /**
     * @param Middleware        $middleware
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @return MessageInterface[]
     */
    private function applyMiddleware(Middleware $middleware, RequestInterface $request, ResponseInterface $response)
    {
        return $middleware->apply($request, $response);
    }
}
