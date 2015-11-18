<?php
namespace Ents\HttpMvcService\Framework\Exception;

interface ExceptionWithHttpStatus
{
    /**
     * @return int
     */
    public function getHttpStatusCode();
}
