<?php
namespace Sainsburys\HttpService\Components\ErrorHandling\Exceptions;

interface ExceptionWithHttpStatus
{
    /**
     * @return int
     */
    public function getHttpStatusCode();
}
