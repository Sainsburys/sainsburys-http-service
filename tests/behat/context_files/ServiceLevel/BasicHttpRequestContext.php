<?php
namespace Ents\HttpMvcService\Test\ServiceLevel;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Context\Context;

class BasicHttpRequestContext implements Context, SnippetAcceptingContext
{


    /**
     * @When I send a GET request to :arg1
     */
    public function iSendAGetRequestTo($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then I should get status code :arg1
     */
    public function iShouldGetStatusCode($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Given there is a resource of type :arg3 with ID :arg4 and body :arg5
     */
    public function thereIsAResourceOfTypeWithIdAndBody($arg1, $arg2, $arg3, $arg4, $arg5)
    {
        throw new PendingException();
    }

    /**
     * @Then I should get response body :arg3
     */
    public function iShouldGetResponseBody($arg1, $arg2, $arg3)
    {
        throw new PendingException();
    }
}
