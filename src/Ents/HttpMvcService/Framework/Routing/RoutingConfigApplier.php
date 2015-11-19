<?php
namespace Ents\HttpMvcService\Framework\Routing;

use Ents\HttpMvcService\Framework\Exception\InvalidControllerException;
use Pimple\Container;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\Slim as SlimApplication;
use Psr\Http\Message\RequestInterface;

class RoutingConfigApplier
{
    /**
     * @param SlimApplication $slimApplication
     * @param Route           $route
     * @param Container       $container
     */
    public function configureApplicationWithRoute(SlimApplication $slimApplication, Route $route, Container $container)
    {
        $controllerCallback = $this->getControllerCallbackForRoute($route, $container);

        switch ($route->httpVerb()) {
            case 'GET' :
                $slimApplication->get($route->pathExpression(), $controllerCallback);
                break;
            case 'DELETE' :
                $slimApplication->delete($route->pathExpression(), $controllerCallback);
                break;
            case 'PUT' :
                $slimApplication->put($route->pathExpression(), $controllerCallback);
                break;
            case 'POST' :
                $slimApplication->post($route->pathExpression(), $controllerCallback);
                break;
        }
    }

    /**
     * @param Route     $route
     * @param Container $container
     * @return callable
     */
    private function getControllerCallbackForRoute(Route $route, Container $container)
    {
        return function () use ($route, $container) {

            $controllerServiceId = $route->controllerServiceId();
            $actionMethodName    = $route->actionMethodName();
            $controllerObject    = $container[$controllerServiceId];

            $response = $controllerObject->$actionMethodName();

            if (is_array($response)) {
                return new Response(json_encode($response));
            } elseif ($response instanceof ResponseInterface) {
                return $response;
            }

            throw new InvalidControllerException();
        };
    }
}
