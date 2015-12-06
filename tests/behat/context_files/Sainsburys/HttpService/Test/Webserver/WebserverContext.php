<?php
namespace Sainsburys\HttpService\Test\Webserver;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\ResponseInterface;

class WebserverContext implements Context, SnippetAcceptingContext
{
    /** @var GuzzleClient */
    private $guzzleClient;

    /** @var ResponseInterface */
    private $responseReceived;

    public function __construct()
    {
        $this->guzzleClient = new GuzzleClient();
    }

    /**
     * @When I send a GET request to :path
     */
    public function iSendAGetRequestTo($path)
    {
        $this->responseReceived = $this->guzzleClient->get('http://localhost:8081' . $path, ['exceptions' => false]);
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
        \PHPUnit_Framework_Assert::assertEquals(
            $expectedResponseBody,
            $this->responseReceived->getBody()->getContents()
        );
    }

    /**
     * @Given my API is coded to return a the response :response for route :route
     */
    public function myApiIsCodedToReturnAResponseForUrl($response, $route) {}

    /**
     * @Given my API is coded to throw an exception with an HTTP status code on it
     */
    public function myApiIsCodedToThrowAnExceptionWithAnHttpStatusCodeOnIt() {}

    /**
     * @Given my API is coded to throw a generic, uncaught exception in the controller
     */
    public function myApiIsCodedToThrowAGenericUncaughtExceptionInTheController() {}

    /**
     * @Given my API is coded put the correct Content-Type with a middleware
     */
    public function myApiIsCodedPutTheCorrectContentTypeWithAMiddleware() {}

    /**
     * @Given my API is coded not to have a route for :pathWithNoRoute
     */
    public function myApiIsCodedNotToHaveARouteFor($pathWithNoRoute) {}

    /**
     * @Then the response body should contain :partialResponseBody
     */
    public function theResponseBodyShouldContain($partialResponseBody)
    {
        \PHPUnit_Framework_Assert::assertContains(
            $partialResponseBody,
            $this->responseReceived->getBody()->getContents()
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
}
