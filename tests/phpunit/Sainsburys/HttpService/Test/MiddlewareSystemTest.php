<?php
namespace Sainsburys\HttpService\Test;

use PHPUnit_Framework_TestCase as TestCase;
use Sainsburys\HttpService\Framework\Middleware\BeforeMiddleware\ConvertToJsonResponseObject;
use Sainsburys\HttpService\Framework\Middleware\Manager\MiddlewareManager;
use Sainsburys\HttpService\Framework\Middleware\RequestAndResponse;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\Response;

class MiddlewareSystemTest extends TestCase
{
    /** @var MiddlewareManager */
    private $middlewareManager;

    public function setUp()
    {
        $this->middlewareManager = new MiddlewareManager();
        $this->middlewareManager->addToStartOfBeforeMiddlewareList(new ConvertToJsonResponseObject());
    }

    public function testConvertingResponseObject()
    {
        $originalResponseHeaders = new Headers(['Content-Type' => 'application/xml']);
        $originalResponse = new Response(201, $originalResponseHeaders);
        $originalRequest = $this->getMock('\Slim\Http\Request', [], [], '', false, false); /** @var $originalRequest Request */

        $originalRequestAndResponse = new RequestAndResponse($originalRequest, $originalResponse);

        // ACT
        $finalRequestAndResponse = $this->middlewareManager->applyBeforeMiddlewares($originalRequestAndResponse);
        $finalResponse = $finalRequestAndResponse->response();

        // ASSERT
        $this->assertEquals(201, $finalResponse->getStatusCode());
        $this->assertEquals('application/json', $finalResponse->getHeader('Content-Type')[0]);
    }
}
