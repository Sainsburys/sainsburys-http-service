<?php
namespace Ents\HttpMvcService\Dev;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class SimpleController
{
    /** @var string */
    private $responseBody = '{"name": "Eminem"}';

    /**
     * @param string $responseBody
     */
    public function setResponse($responseBody)
    {
        $this->responseBody = $responseBody;
    }

    /**
     * @return ResponseInterface
     */
    public function simpleAction()
    {
        return new Response(200, [], $this->responseBody);
    }
}
