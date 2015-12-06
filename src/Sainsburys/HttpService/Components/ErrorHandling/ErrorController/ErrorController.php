<?php
namespace Sainsburys\HttpService\Components\ErrorHandling\ErrorController;

use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

interface ErrorController
{
    public function handleError(\Exception $exception, LoggerInterface $logger): ResponseInterface;
}
