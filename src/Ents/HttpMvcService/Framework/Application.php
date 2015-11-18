<?php
namespace Ents\HttpMvcService\Framework;

use Pimple\Container;
use Psr\Http\Message\RequestInterface;
use Slim\Slim as SlimApplication;
use Ents\HttpMvcService\Framework\Routing\RoutingConfigReader;
use Ents\HttpMvcService\Framework\Routing\Route;

class Application
{
    /** @var SlimApplication */
    private $slimApplication;

    /** @var RoutingConfigReader */
    private $routingConfigReader;

    /** @var Container */
    private $container;

    /** @var bool */
    private $haveSomeRoutesConfigured = false;

    /**
     * @param SlimApplication     $slimApplication
     * @param RoutingConfigReader $routingConfigReader
     */
    public function __construct(SlimApplication $slimApplication, RoutingConfigReader $routingConfigReader)
    {
        $this->slimApplication = $slimApplication;
        $this->routingConfigReader = $routingConfigReader;
    }

    /**
     * @param Container $container
     */
    public function takeContainerWithControllersConfigured(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $path
     */
    public function takeRoutingConfig($path)
    {
        $routes = $this->routingConfigReader->getRoutesFromFile($path);

        foreach ($routes as $route) {

            $controllerCallback = $this->getControllerCallbackForRoute($route);

            switch ($route->httpVerb()) {
                case 'GET' :
                    $this->slimApplication->get($route->pathExpression(), $controllerCallback);
                    break;
                case 'DELETE' :
                    $this->slimApplication->delete($route->pathExpression(), $controllerCallback);
                    break;
                case 'PUT' :
                    $this->slimApplication->put($route->pathExpression(), $controllerCallback);
                    break;
                case 'POST' :
                    $this->slimApplication->post($route->pathExpression(), $controllerCallback);
                    break;
            }

        }

        $this->haveSomeRoutesConfigured = true;
    }

    public function run()
    {
        if (!$this->container) {
            throw new \RuntimeException('Must call takeContainerWithControllersConfigured() before run()');
        }

        if (!$this->haveSomeRoutesConfigured) {
            throw new \RuntimeException('Must call takeRoutingConfig() before run()');
        }

        $this->slimApplication->run();
    }

    /**
     * @param Route $route
     * @return callable
     */
    private function getControllerCallbackForRoute(Route $route)
    {
        $container = $this->container;

        return function (RequestInterface $request) use ($route, $container) {

            $controllerServiceId = $route->controllerServiceId();
            $actionMethodName = $route->actionMethodName();
            $controllerObject = $container[$controllerServiceId];

            $httpResponse = $controllerObject->$actionMethodName($request);
            return $httpResponse;
        };
    }
}
