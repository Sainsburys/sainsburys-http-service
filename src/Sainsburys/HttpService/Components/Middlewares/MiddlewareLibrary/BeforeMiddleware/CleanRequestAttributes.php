<?php
namespace Sainsburys\HttpService\Components\Middlewares\MiddlewareLibrary\BeforeMiddleware;

use Sainsburys\HttpService\Components\Middlewares\MiddlewareInterfaces\BeforeMiddleware;
use Sainsburys\HttpService\Components\Middlewares\Http\RequestAndResponse;

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
