<?php
namespace Sainsburys\HttpService\Components\ErrorHandling\Exceptions\WithStatusCode;

use Sainsburys\HttpService\Components\ErrorHandling\Exceptions\ExceptionWithHttpStatus;
use Teapot\StatusCode\Http;

class NotAuthorisedException extends \RuntimeException implements ExceptionWithHttpStatus
{
    /**
     * @param string|null     $message
     * @param int|null        $code
     * @param \Exception|null $previous
     */
    public function __construct($message = null, $code = null, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->message = 'Access to resource is not authorised.';
    }

    /**
     * @return int
     */
    public function getHttpStatusCode()
    {
        return Http::UNAUTHORIZED; //401
    }
}
