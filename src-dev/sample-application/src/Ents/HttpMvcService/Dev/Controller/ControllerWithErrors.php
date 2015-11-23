<?php
namespace Ents\HttpMvcService\Dev\Controller;

use Ents\HttpMvcService\Framework\Exception\WithStatusCode\NotAuthorisedException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class ControllerWithErrors
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     *
     * @throws \Exception
     */
    public function throwGenericException(ServerRequestInterface $request, ResponseInterface $response)
    {
        throw new \Exception('Exception message');
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     *
     * @throws \Exception
     */
    public function throwNotAuthorisedException(ServerRequestInterface $request, ResponseInterface $response)
    {
        throw new NotAuthorisedException();
    }
}
