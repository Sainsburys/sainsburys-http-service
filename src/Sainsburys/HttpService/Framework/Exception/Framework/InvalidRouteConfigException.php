<?php
namespace Sainsburys\HttpService\Framework\Exception\Framework;

use Sainsburys\HttpService\Framework\Exception\ExceptionWithHttpStatus;
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
