<?php
namespace Ents\HttpMvcService\Framework\Exception;

class InvalidControllerException extends \RuntimeException implements ExceptionWithHttpStatus
{
    /**
     * @param string          $message
     * @param int|null        $code
     * @param \Exception|null $previous
     */
    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        $this->message = 'A controller failed to return an array or a \Psr\Http\Message\ResponseInterface';
    }

    /**
     * @return int
     */
    public function getHttpStatusCode()
    {
        return 500;
    }
}
