<?php
namespace Sainsburys\HttpService\Components\ErrorHandling;

use Psr\Http\Message\ResponseInterface;

interface ErrorController
{
    /**
     * @param \Exception $exception
     * @return ResponseInterface
     */
    public function handleError(\Exception $exception);
}
