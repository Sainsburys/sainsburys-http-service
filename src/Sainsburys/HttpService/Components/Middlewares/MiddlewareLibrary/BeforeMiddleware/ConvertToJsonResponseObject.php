<?php
namespace Sainsburys\HttpService\Components\Middlewares\MiddlewareLibrary\BeforeMiddleware;

use Sainsburys\HttpService\Components\Middlewares\MiddlewareInterfaces\BeforeMiddleware;
use Sainsburys\HttpService\Components\Middlewares\Http\RequestAndResponse;
use Zend\Diactoros\Response\JsonResponse;

class ConvertToJsonResponseObject implements BeforeMiddleware
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'convert-to-json-response-object';
    }

    /**
     * @param RequestAndResponse $originalRequestAndResponse
     * @return RequestAndResponse
     */
    public function apply(RequestAndResponse $originalRequestAndResponse)
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
