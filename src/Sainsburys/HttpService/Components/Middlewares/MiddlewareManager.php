<?php
namespace Sainsburys\HttpService\Components\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Sainsburys\HttpService\Components\Middlewares\MiddlewareInterfaces\BeforeMiddleware;
use Sainsburys\HttpService\Components\Middlewares\MiddlewareInterfaces\AfterMiddleware;
use Sainsburys\HttpService\Components\Middlewares\Http\RequestAndResponse;

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

    public function addToStartOfBeforeMiddlewareList(BeforeMiddleware $beforeMiddleware)
    {
        array_unshift($this->beforeMiddlewareList, $beforeMiddleware);
    }

    public function addToEndOfBeforeMiddlewareList(BeforeMiddleware $beforeMiddleware)
    {
        $this->beforeMiddlewareList[] = $beforeMiddleware;
    }

    public function clearAfterMiddlewareList()
    {
        $this->afterMiddlewareList = [];
    }

    public function addToStartOfAfterMiddlewareList(AfterMiddleware $afterMiddleware)
    {
        array_unshift($this->afterMiddlewareList, $afterMiddleware);
    }

    public function addToEndOfAfterMiddlewareList(AfterMiddleware $afterMiddleware)
    {
        $this->afterMiddlewareList[] = $afterMiddleware;
    }

    public function applyBeforeMiddlewares(RequestAndResponse $requestAndResponse): RequestAndResponse
    {
        foreach ($this->beforeMiddlewareList as $beforeMiddleware) {
            $requestAndResponse = $beforeMiddleware->apply($requestAndResponse);
        }

        return $requestAndResponse;
    }

    public function applyAfterMiddlewares(ResponseInterface $response): ResponseInterface
    {
        foreach ($this->afterMiddlewareList as $afterMiddleware) {
            $response = $afterMiddleware->apply($response);
        }
        return $response;
    }
}
