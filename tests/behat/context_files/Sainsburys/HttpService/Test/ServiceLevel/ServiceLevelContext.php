<?php
namespace Sainsburys\HttpService\Test\ServiceLevel;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Psr\Http\Message\ResponseInterface;
use Sainsburys\HttpService\Dev\MyDiConfig;
use SamBurns\Pimple3ContainerInterop\ServiceContainer;
use UltraLite\Container\Container;
use Zend\Diactoros\Request;
use Sainsburys\HttpService\Application;
use Zend\Diactoros\ServerRequest;

class ServiceLevelContext implements Context, SnippetAcceptingContext
{
    /** @var ResponseInterface */
    private $responseReceived;

    /** @var Application */
    private $application;

    public function __construct()
    {
        $routingConfigFile = __DIR__ . '/../../../../../../../src-dev/sample-application/config/routing.php';
        $diConfigFile      = __DIR__ . '/../../../../../../../src-dev/sample-application/config/di.php';

        $containerWithControllers = new Container();
        $containerWithControllers->configureFromFile($diConfigFile);

        $this->application = Application::factory([$routingConfigFile], $containerWithControllers);

        $this->startSession();
    }

    /**
     * @When I send a GET request to :path
     */
    public function iSendAGetRequestTo($path)
    {
        $request = new ServerRequest([], [], 'http://api.com' . $path, 'GET');
        $this->responseReceived = $this->application->run($request);
    }

    /**
     * @Then I should get status code :expectedStatusCode
     */
    public function iShouldGetStatusCode($expectedStatusCode)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            $expectedStatusCode,
            $this->responseReceived->getStatusCode()
        );
    }

    /**
     * @Then I should get response body :expectedResponseBody
     */
    public function iShouldGetResponseBody($expectedResponseBody)
    {
        $this->responseReceived->getBody()->rewind();
        $responseBodyReceived = $this->responseReceived->getBody()->getContents();

        \PHPUnit_Framework_Assert::assertEquals(
            $expectedResponseBody,
            $responseBodyReceived
        );
    }

    /**
     * @Given my API is coded to return a the response :response for route :route
     */
    public function myApiIsCodedToReturnAResponseForUrl($response, $route)
    {
    }

    /**
     * @Given my API is coded to throw an exception with an HTTP status code on it
     */
    public function myApiIsCodedToThrowAnExceptionWithAnHttpStatusCodeOnIt()
    {
    }

    /**
     * @Given my API is coded to throw a generic, uncaught exception in the controller
     */
    public function myApiIsCodedToThrowAGenericUncaughtExceptionInTheController()
    {
    }

    /**
     * @Given my API is coded put the correct Content-Type with a middleware
     */
    public function myApiIsCodedPutTheCorrectContentTypeWithAMiddleware()
    {
    }

    /**
     * @Given my API is coded not to have a route for :pathWithNoRoute
     */
    public function myApiIsCodedNotToHaveARouteFor($pathWithNoRoute)
    {
    }

    /**
     * @Then the response body should contain :partialResponseBody
     */
    public function theResponseBodyShouldContain($partialResponseBody)
    {
        $this->responseReceived->getBody()->rewind();
        $responseBodyReceived = $this->responseReceived->getBody()->getContents();

        \PHPUnit_Framework_Assert::assertContains(
            $partialResponseBody,
            $responseBodyReceived
        );
    }

    /**
     * @When the response headers should contain :expectedHeader
     */
    public function theResponseHeadersShouldContain($expectedHeader)
    {
        list($headerTitle, $expectedHeaderValue) = explode(':', $expectedHeader);
        $expectedHeaderValue = trim($expectedHeaderValue);

        $headerValuesReceived = $this->responseReceived->getHeader($headerTitle);

        \PHPUnit_Framework_Assert::assertEquals($expectedHeaderValue, $headerValuesReceived[0]);
    }

    private function startSession()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
    }
}
