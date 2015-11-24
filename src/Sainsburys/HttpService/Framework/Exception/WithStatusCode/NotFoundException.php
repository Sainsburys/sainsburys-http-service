<?php
namespace Sainsburys\HttpService\Framework\Exception\WithStatusCode;

use Sainsburys\HttpService\Framework\Exception\ExceptionWithHttpStatus;
use Teapot\StatusCode\Http;

class NotFoundException extends \RuntimeException implements ExceptionWithHttpStatus
{
    /**
     * @param string|null     $message
     * @param int|null        $code
     * @param \Exception|null $previous
     */
    public function __construct($message = null, $code = null, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->message = 'Resource or endpoint not found.';
    }

    /**
     * @return int
     */
    public function getHttpStatusCode()
    {
        return Http::NOT_FOUND; //404
    }
}
