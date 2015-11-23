<?php
namespace Ents\HttpMvcService\Framework\Exception\Framework;

use Ents\HttpMvcService\Framework\Exception\ExceptionWithHttpStatus;
use Teapot\StatusCode\Http;

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
        return Http::INTERNAL_SERVER_ERROR; //500
    }
}
