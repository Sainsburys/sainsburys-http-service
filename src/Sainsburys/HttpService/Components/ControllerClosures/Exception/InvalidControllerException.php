<?php
namespace Sainsburys\HttpService\Components\ControllerClosures\Exception;

use Sainsburys\HttpService\Components\ErrorHandling\Exceptions\ExceptionWithHttpStatus;
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
        $this->message = 'A controller failed to return a \Psr\Http\Message\ResponseInterface';
    }

    /**
     * @return int
     */
    public function getHttpStatusCode()
    {
        return Http::INTERNAL_SERVER_ERROR; //500
    }
}
