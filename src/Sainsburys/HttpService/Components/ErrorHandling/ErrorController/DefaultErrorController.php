<?php
namespace Sainsburys\HttpService\Components\ErrorHandling\ErrorController;

use Psr\Log\LoggerInterface;
use Sainsburys\HttpService\Components\ErrorHandling\Exceptions\ExceptionWithHttpStatus;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

class DefaultErrorController implements ErrorController
{
    /**
     * @param \Exception      $exception
     * @param LoggerInterface $logger
     * @return ResponseInterface
     */
    public function handleError(\Exception $exception, LoggerInterface $logger)
    {
        $responseBodyArray = [
            'exception-class' => get_class($exception),
            'message'         => $exception->getMessage(),
            'stack-trace'     => $exception->getTraceAsString()
        ];

        $logger->critical($exception->getMessage(), $responseBodyArray);

        $statusCode = $exception instanceof ExceptionWithHttpStatus ? $exception->getHttpStatusCode() : 500;

        return new JsonResponse($responseBodyArray, $statusCode);
    }
}
