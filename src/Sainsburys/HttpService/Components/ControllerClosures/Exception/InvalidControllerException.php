<?php
namespace Sainsburys\HttpService\Components\ControllerClosures\Exception;

use Sainsburys\HttpService\Components\ErrorHandling\Exceptions\ExceptionWithHttpStatus;
use Teapot\StatusCode\Http;

class InvalidControllerException extends \RuntimeException implements ExceptionWithHttpStatus
{
    public function __construct(string $message = "", int $code = 0, \Exception $previous = null)
    {
        $this->message = 'A controller failed to return a \Psr\Http\Message\ResponseInterface';
    }

    public function getHttpStatusCode(): int
    {
        return Http::INTERNAL_SERVER_ERROR; //500
    }
}
