<?php
namespace Sainsburys\HttpService\Framework\Middleware\BeforeMiddleware;

use Sainsburys\HttpService\Framework\Middleware\BeforeMiddleware;
use Sainsburys\HttpService\Framework\Middleware\RequestAndResponse;
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
        $newResponse = new JsonResponse([], $originalResponse->getStatusCode(), $originalResponse->getHeaders());
        $newResponse = $newResponse->withoutHeader('Content-Type');
        $newResponse = $newResponse->withHeader('Content-Type', 'application/json');

        return new RequestAndResponse($originalRequestAndResponse->request(), $newResponse);
    }
}
