<?php
namespace Sainsburys\HttpService\Dev\Controller;

use Sainsburys\HttpService\Components\ErrorHandling\Exceptions\WithStatusCode\NotAuthorisedException;
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
