<?php
namespace Sainsburys\HttpService\Framework\Middleware\BeforeMiddleware;

use Sainsburys\HttpService\Framework\Middleware\BeforeMiddleware;
use Sainsburys\HttpService\Framework\Middleware\RequestAndResponse;
use Zend\Diactoros\Response\JsonResponse;

class CleanRequestAttributes implements BeforeMiddleware
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'clean-slim-stuff-from-request-attributes';
    }

    /**
     * @param RequestAndResponse $originalRequestAndResponse
     * @return RequestAndResponse
     */
    public function apply(RequestAndResponse $originalRequestAndResponse)
    {
        $request = $originalRequestAndResponse->request();
        $request = $request->withoutAttribute('route');
        $request = $request->withoutAttribute('route-info');

        return new RequestAndResponse($request, $originalRequestAndResponse->response());
    }
}
