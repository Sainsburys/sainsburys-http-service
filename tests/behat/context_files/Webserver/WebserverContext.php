<?php
namespace Ents\HttpMvcService\Test\Webserver;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;

class WebserverContext implements Context, SnippetAcceptingContext
{
    /**
     * @When I send a GET request to :path
     */
    public function iSendAGetRequestTo($path)
    {
        throw new PendingException();
    }

    /**
     * @Then I should get status code :expectedStatusCode
     */
    public function iShouldGetStatusCode($expectedStatusCode)
    {
        throw new PendingException();
    }

    /**
     * @Then I should get response body :expectedResponseBody
     */
    public function iShouldGetResponseBody($expectedResponseBody)
    {
        throw new PendingException();
    }

    /**
     * @Given my API is coded to return a the response :response for route :route
     */
    public function myApiIsCodedToReturnAResponseForUrl($response, $route)
    {
    }
}
