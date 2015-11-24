<?php
namespace Sainsburys\HttpService\Framework\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class RequestAndResponse
{
    /** @var ServerRequestInterface */
    private $request;

    /** @var ResponseInterface */
    private $response;

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     */
    public function __construct(ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @return ServerRequestInterface
     */
    public function request()
    {
        return $this->request;
    }

    /**
     * @return ResponseInterface
     */
    public function response()
    {
        return $this->response;
    }
}
