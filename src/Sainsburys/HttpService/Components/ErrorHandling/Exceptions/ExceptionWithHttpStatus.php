<?php
namespace Sainsburys\HttpService\Components\ErrorHandling\Exceptions;

interface ExceptionWithHttpStatus
{
    public function getHttpStatusCode(): int;
}
