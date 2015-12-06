<?php
namespace Sainsburys\HttpService\Components\Middlewares\MiddlewareLibrary\BeforeMiddleware;

use Sainsburys\HttpService\Components\Middlewares\MiddlewareInterfaces\BeforeMiddleware;
use Sainsburys\HttpService\Components\Middlewares\Http\RequestAndResponse;

class CleanRequestAttributes implements BeforeMiddleware
{
    public function getName(): string
    {
        return 'clean-slim-stuff-from-request-attributes';
    }

    public function apply(RequestAndResponse $originalRequestAndResponse): RequestAndResponse
    {
        $request = $originalRequestAndResponse->request();

        return new RequestAndResponse(
            $request->withoutAttribute('route')->withoutAttribute('route-info'),
            $originalRequestAndResponse->response()
        );
    }
}
