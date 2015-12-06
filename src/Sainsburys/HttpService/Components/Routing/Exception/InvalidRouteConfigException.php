<?php
namespace Sainsburys\HttpService\Components\Routing\Exception;

use Sainsburys\HttpService\Components\ErrorHandling\Exceptions\ExceptionWithHttpStatus;
use Teapot\StatusCode\Http;

class InvalidRouteConfigException extends \RuntimeException implements ExceptionWithHttpStatus
{
    public function getHttpStatusCode(): int
    {
        return Http::INTERNAL_SERVER_ERROR; //500
    }
}
