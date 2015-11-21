<?php
namespace Ents\HttpMvcService\Dev\Controller;

use Zend\Diactoros\Response\JsonResponse;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

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
        $id = isset($urlVariables['id']) ? $urlVariables['id'] : 999;
        return new JsonResponse([
            'id' => $id,
            'name' => 'Eminem'
        ]);
    }
}
