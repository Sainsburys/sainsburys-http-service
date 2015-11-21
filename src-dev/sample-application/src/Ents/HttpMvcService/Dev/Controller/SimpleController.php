<?php
namespace Ents\HttpMvcService\Dev\Controller;

use Zend\Diactoros\Response\JsonResponse;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Teapot\StatusCode\Http;

class SimpleController
{
    /**
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param string[]          $urlVariables
     *
     * @return ResponseInterface
     */
    public function simpleAction(RequestInterface $request, ResponseInterface $response, array $urlVariables)
    {
        $id = $urlVariables['id'];

        return new JsonResponse(
            [
                'id' => $id,
                'name' => 'Eminem'
            ],
            Http::OK
        );
    }
}
