<?php
namespace Sainsburys\HttpService\Framework\Middleware\Manager;

use Psr\Http\Message\ResponseInterface;
use Sainsburys\HttpService\Framework\Middleware\BeforeMiddleware;
use Sainsburys\HttpService\Framework\Middleware\AfterMiddleware;
use Sainsburys\HttpService\Framework\Middleware\Exception\AfterMiddlewareReturnTypeException;
use Sainsburys\HttpService\Framework\Middleware\Exception\BeforeMiddlewareReturnTypeException;
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
     * @return RequestAndResponse
     */
    public function applyBeforeMiddlewares(RequestAndResponse $requestAndResponse)
    {
        foreach ($this->beforeMiddlewareList as $beforeMiddleware) {
            $requestAndResponse = $beforeMiddleware->apply($requestAndResponse);
            $this->validateBeforeMiddlewareReturnType($beforeMiddleware, $requestAndResponse);
        }

        return $requestAndResponse;
    }

    /**
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function applyAfterMiddlewares(ResponseInterface $response)
    {
        foreach ($this->afterMiddlewareList as $afterMiddleware) {
            $response = $afterMiddleware->apply($response);
            $this->validateAfterMiddlewareReturnType($afterMiddleware, $response);
        }
        return $response;
    }

    /**
     * @param BeforeMiddleware $middleware
     * @param mixed            $thingToValidate
     */
    private function validateBeforeMiddlewareReturnType(BeforeMiddleware $middleware, $thingToValidate)
    {
        if (! $thingToValidate instanceof RequestAndResponse) {
            throw BeforeMiddlewareReturnTypeException::constructFromMiddleware($middleware);
        }
    }

    /**
     * @param AfterMiddleware $middleware
     * @param mixed           $thingToValidate
     */
    private function validateAfterMiddlewareReturnType(AfterMiddleware $middleware, $thingToValidate)
    {
        if (! $thingToValidate instanceof ResponseInterface) {
            throw AfterMiddlewareReturnTypeException::constructFromMiddleware($middleware);
        }
    }
}
