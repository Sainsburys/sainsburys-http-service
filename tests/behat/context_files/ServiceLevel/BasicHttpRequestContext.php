<?php
namespace Ents\HttpMvcService\Test\ServiceLevel;

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Context\Context;
use Pimple\Container;
use Ents\HttpMvcService\Di\ServiceProvider;
use Ents\HttpMvcService\Framework\FrontController;
use Ents\HttpMvcService\Framework\Router;
use Ents\HttpMvcService\Dev\SimpleController;
use Ents\HttpMvcService\Dev\SimpleHttpRequest;
use Psr\Http\Message\ResponseInterface;

class BasicHttpRequestContext implements Context, SnippetAcceptingContext
{
    /** @var Container */
    private $container;

    /** @var Router */
    private $router;

    /** @var FrontController */
    private $frontController;

    /** @var ResponseInterface */
    private $responseReceived;

    public function __construct()
    {
        $this->container = new Container();
        $serviceProvider = new ServiceProvider();
        $serviceProvider->configure($this->container);
        $this->router = $this->container->get('ents.http-mvc-service.router');
        $this->frontController = $this->container->get('ents.http-mvc-service.front-controller');
    }

    /**
     * @Given my API is coded to return a the response :response for route :route
     */
    public function myApiIsCodedToReturnAResponseForUrl($response, $route)
    {
        $requestVerbs = ['GET'];
        $controllerServiceId = 'ents.http-mvc-service.dev.sample-controller';
        $actionMethodName = 'simpleAction';


        $controller = new SimpleController();
        $controller->setResponse($response);
        $this->container->set($controllerServiceId ,$controller);

        $this->router->addRouteSpecification($route, $requestVerbs, $controllerServiceId, $actionMethodName);
    }

    /**
     * @When I send a GET request to :path
     */
    public function iSendAGetRequestTo($path)
    {
        $request = new SimpleHttpRequest();
        $request->setPath($path);
        $this->responseReceived = $this->frontController->getResponseForRequest($request);
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
            $this->responseReceived->getBody()
        );
    }
}
