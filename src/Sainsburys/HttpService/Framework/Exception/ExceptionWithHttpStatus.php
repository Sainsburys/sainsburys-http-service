<?php
namespace Sainsburys\HttpService\Framework\Exception;

interface ExceptionWithHttpStatus
{
    /**
     * @return int
     */
    public function getHttpStatusCode();
}
