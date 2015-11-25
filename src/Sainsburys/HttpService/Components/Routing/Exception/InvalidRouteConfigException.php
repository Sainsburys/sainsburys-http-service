<?php
namespace Sainsburys\HttpService\Components\Routing\Exception;

use Sainsburys\HttpService\Components\HttpExceptions\ExceptionWithHttpStatus;
use Teapot\StatusCode\Http;

class InvalidRouteConfigException extends \RuntimeException implements ExceptionWithHttpStatus
{
    /**
     * @return int
     */
    public function getHttpStatusCode()
    {
        return Http::INTERNAL_SERVER_ERROR; //500
    }
}
