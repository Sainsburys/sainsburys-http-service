<?php
namespace Sainsburys\HttpService\Components\Middlewares\MiddlewareLibrary\BeforeMiddleware;

use Sainsburys\HttpService\Components\Middlewares\MiddlewareInterfaces\BeforeMiddleware;
use Sainsburys\HttpService\Components\Middlewares\Http\RequestAndResponse;
use Zend\Diactoros\Response\JsonResponse;

class ConvertToJsonResponseObject implements BeforeMiddleware
{
    public function getName(): string
    {
        return 'convert-to-json-response-object';
    }

    public function apply(RequestAndResponse $originalRequestAndResponse): RequestAndResponse
    {
        $originalResponse = $originalRequestAndResponse->response();
        $headers = $originalResponse->getHeaders();
        $headers['Content-Type'] = 'application/json';

        return new RequestAndResponse(
            $originalRequestAndResponse->request(),
            new JsonResponse([], $originalResponse->getStatusCode(), $headers)
        );
    }
}
