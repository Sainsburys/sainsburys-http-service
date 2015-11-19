<?php
namespace Ents\HttpMvcService\Framework\Routing;

use Ents\HttpMvcService\Framework\Exception\InvalidControllerException;
use Pimple\Container;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\App as SlimApplication;
use Zend\Diactoros\Response\JsonResponse;

class RoutingConfigApplier
{
    /**
     * @param SlimApplication $slimApplication
     * @param Route           $route
     * @param Container       $container
     */
    public function configureApplicationWithRoute(SlimApplication $slimApplication, Route $route, Container $container)
    {
        $controllerCallback = $this->getControllerCallbackForRoute($route, $container, $slimApplication);

        $slimApplication->map(
            [$route->httpVerb()],
            $route->pathExpression(),
            $controllerCallback
        )->setName($route->name());
    }

    /**
     * @param Route           $route
     * @param Container       $container
     * @param SlimApplication $slimApplication
     * @return callable
     */
    private function getControllerCallbackForRoute(Route $route, Container $container, SlimApplication $slimApplication)
    {
        return function (RequestInterface $request, ResponseInterface $response, array $urlVars) use ($route, $container, $slimApplication) {

            $controllerServiceId = $route->controllerServiceId();
            $actionMethodName    = $route->actionMethodName();
            $controllerObject    = $container[$controllerServiceId];

            $controllerResponse = $controllerObject->$actionMethodName($request, $response, $urlVars);

            if (is_null($response)) {
                $controllerResponse = $response;
            } elseif (is_array($response)) {
                $controllerResponse = new JsonResponse($response);
            }

            if (!$controllerResponse instanceof ResponseInterface) {
                throw new InvalidControllerException(
                    "Didn't get a valid HTTP Response object, and couldn't generate one from controller output"
                );
            }
            return $controllerResponse;
        };
    }
}
