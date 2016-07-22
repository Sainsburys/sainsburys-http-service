<?php
namespace Sainsburys\HttpService\Components\Middlewares\MiddlewareLibrary\BeforeMiddleware;

use Sainsburys\HttpService\Components\Middlewares\Http\RequestAndResponse;
use Sainsburys\HttpService\Components\Middlewares\MiddlewareInterfaces\BeforeMiddleware;
use Sainsburys\HttpService\Components\ErrorHandling\Exceptions\WithStatusCode\MalformedJsonRequest;

class UnpackJsonParametersToArray implements BeforeMiddleware
{

    private $originalParams;

    private $proxyInjectedParams;

    public function __construct(string $originalParams, string $proxyInjectedParams)
    {
        $this->originalParams      = $originalParams;
        $this->proxyInjectedParams = $proxyInjectedParams;
    }

    public function apply(RequestAndResponse $originalRequestAndResponse): RequestAndResponse
    {
        $request = $originalRequestAndResponse->request();
        $json    = $originalRequestAndResponse->request()->getBody()->getContents() ?? "{}";
        $originalRequestAndResponse->request()->getBody()->rewind();

        if ($request->getMethod() == 'GET') {
            return $originalRequestAndResponse;
        }

        $proxyParams = json_decode($this->ifEmptyDefaultToEmptyJson($json), true);

        if (json_last_error()) {
            throw new MalformedJsonRequest(json_last_error_msg() ." " . $json);
        }

        $originalParams = $proxyParams[$this->originalParams] ?? [];

        foreach ($proxyParams[$this->proxyInjectedParams] ?? [] as $key => $value) {
            $k                  = preg_replace('/-/', '_', $key);
            $originalParams[$k] = $value;
        }

        return new RequestAndResponse(
            $newRequest = $request->withParsedBody($originalParams),
            $originalRequestAndResponse->response()
        );
    }

    public function getName(): string
    {
        return 'unpack-json-parameters-to-array';
    }

    public function ifEmptyDefaultToEmptyJson(string $json)
    {
        return empty($json) ? '{}' : $json;
    }
}