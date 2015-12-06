<?php
namespace Sainsburys\HttpService\Components\ErrorHandling\ErrorController;

use Psr\Log\LoggerInterface;
use Sainsburys\HttpService\Components\ErrorHandling\Exceptions\ExceptionWithHttpStatus;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

class DefaultErrorController implements ErrorController
{
    public function handleError(\Exception $exception, LoggerInterface $logger): ResponseInterface
    {
        $this->logError($exception, $logger);
        $response = $this->prepareHttpResponse($exception);

        return $response;
    }

    private function logError(\Exception $exception, LoggerInterface $logger)
    {
        $logger->critical(
            get_class($exception) . ': ' . $exception->getMessage(),
            $exception->getTrace()
        );
    }

    private function prepareHttpResponse(\Exception $exception): JsonResponse
    {
        $responseBodyArray = [
            'exception-class' => get_class($exception),
            'message'         => $exception->getMessage(),
            'stack-trace'     => $exception->getTrace()
        ];

        $statusCode = $exception instanceof ExceptionWithHttpStatus ? $exception->getHttpStatusCode() : 500;

        return new JsonResponse($responseBodyArray, $statusCode);
    }
}
