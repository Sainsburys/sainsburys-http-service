<?php
namespace Ents\HttpMvcService\Framework\Exception;

class InvalidRouteConfigException extends \RuntimeException implements ExceptionWithHttpStatus
{
    /**
     * @return int
     */
    public function getHttpStatusCode()
    {
        return 500;
    }
}
