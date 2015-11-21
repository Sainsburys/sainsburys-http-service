<?php
namespace Ents\HttpMvcService\Dev\Controller;

use Ents\HttpMvcService\Framework\Exception\WithStatusCode\NotAuthorisedException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ControllerWithErrors
{
    /**
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param string[]          $urlVariables
     *
     * @throws \Exception
     */
    public function throwGenericException(RequestInterface $request, ResponseInterface $response, array $urlVariables)
    {
        throw new \Exception('Exception message');
    }

    /**
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param string[]          $urlVariables
     *
     * @throws \Exception
     */
    public function throwNotAuthorisedException(RequestInterface $request, ResponseInterface $response, array $urlVariables)
    {
        throw new NotAuthorisedException();
    }
}
