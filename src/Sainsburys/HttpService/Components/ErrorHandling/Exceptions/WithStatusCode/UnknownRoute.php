<?php
namespace Sainsburys\HttpService\Components\ErrorHandling\Exceptions\WithStatusCode;

use Sainsburys\HttpService\Components\ErrorHandling\Exceptions\ExceptionWithHttpStatus;
use Teapot\StatusCode\Http;

class UnknownRoute extends \RuntimeException implements ExceptionWithHttpStatus
{
    public function __construct(string $message = null, int $code = null, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->message = 'No route configured for request.';
    }

    public function getHttpStatusCode(): int
    {
        return Http::NOT_FOUND; //404
    }
}
