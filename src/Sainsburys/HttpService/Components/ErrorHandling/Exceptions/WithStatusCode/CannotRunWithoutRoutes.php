<?php
namespace Sainsburys\HttpService\Components\ErrorHandling\Exceptions\WithStatusCode;

use Sainsburys\HttpService\Components\ErrorHandling\Exceptions\ExceptionWithHttpStatus;
use Teapot\StatusCode\Http;

class CannotRunWithoutRoutes extends \RuntimeException implements ExceptionWithHttpStatus
{
    public function __construct(string $message = null, int $code = null, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->message = 'Must call takeRoutingConfigs() before run().  Try using Application::factory() to create the Application';
    }

    public function getHttpStatusCode(): int
    {
        return Http::INTERNAL_SERVER_ERROR; //500
    }
}
