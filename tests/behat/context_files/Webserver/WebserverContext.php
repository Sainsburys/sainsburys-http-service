<?php
namespace Ents\HttpMvcService\Test\Webserver;

use Behat\Behat\Tester\Exception\PendingException;
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
        $this->responseReceived = $this->guzzleClient->get('localhost:8081' . $path, ['exceptions' => false]);
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
     * @Then the response body should contain :partialResponseBody
     */
    public function theResponseBodyShouldContain($partialResponseBody)
    {
        \PHPUnit_Framework_Assert::assertContains(
            $partialResponseBody,
            $this->responseReceived->getBody()->getContents()
        );
    }
}
