<?php
namespace Ents\HttpMvcService\Framework\ErrorHandling;

use Ents\HttpMvcService\Framework\Exception\ExceptionWithHttpStatus;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

class DefaultErrorController implements ErrorController
{
    /**
     * @param \Exception $exception
     * @return ResponseInterface
     */
    public function handleError(\Exception $exception)
    {
        $responseBodyArray = [
            'exception-class' => get_class($exception),
            'message'         => $exception->getMessage(),
            'stack-trace'     => $exception->getTraceAsString()
        ];

        $statusCode = $exception instanceof ExceptionWithHttpStatus ? $exception->getHttpStatusCode() : 500;

        return new JsonResponse($responseBodyArray, $statusCode);
    }
}
