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

        return new RequestAndResponse(
            $request->withoutAttribute('route')->withoutAttribute('route-info'),
            $originalRequestAndResponse->response()
        );
    }
}
