<?php
namespace Sainsburys\HttpService\Components\ErrorHandling\Exceptions\WithStatusCode;

use Sainsburys\HttpService\Components\ErrorHandling\Exceptions\ExceptionWithHttpStatus;
use Teapot\StatusCode\Http;

class MalformedJsonRequest extends \RuntimeException implements ExceptionWithHttpStatus
{

    public function getHttpStatusCode(): int
    {
        return Http::BAD_REQUEST;
    }
}