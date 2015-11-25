<?php
namespace Sainsburys\HttpService\Components\HttpExceptions;

interface ExceptionWithHttpStatus
{
    /**
     * @return int
     */
    public function getHttpStatusCode();
}
