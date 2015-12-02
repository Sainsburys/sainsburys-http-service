<?php
namespace Sainsburys\HttpService\Components\ErrorHandling\ErrorController;

use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

interface ErrorController
{
    /**
     * @param \Exception      $exception
     * @param LoggerInterface $logger
     * @return ResponseInterface
     */
    public function handleError(\Exception $exception, LoggerInterface $logger);
}
