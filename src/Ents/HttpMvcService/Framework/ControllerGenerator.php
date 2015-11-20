<?php
namespace Ents\HttpMvcService\Framework;

use Ents\HttpMvcService\Framework\Exception\InvalidControllerException;
use Pimple\Container;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\App as SlimApplication;
use Zend\Diactoros\Response\JsonResponse;
use Ents\HttpMvcService\Framework\Routing\Route;

class ControllerGenerator
{
    /**
     * @param Route           $route
     * @param Container       $container
     * @param SlimApplication $slimApplication
     * @return callable
     */
    public function getControllerCallbackForRoute(Route $route, Container $container, SlimApplication $slimApplication)
    {
        $controllerServiceId = $route->controllerServiceId();
        $actionMethodName    = $route->actionMethodName();

        return
            function (RequestInterface $request, ResponseInterface $response, array $urlVars) use (
                $route, $container, $slimApplication, $controllerServiceId, $actionMethodName
            ) {
                $controllerObject   = $container[$controllerServiceId];
                $controllerResponse = $controllerObject->$actionMethodName($request, $response, $urlVars);

                if (is_null($response)) {
                    $controllerResponse = $response;
                } elseif (is_array($response)) {
                    $controllerResponse = new JsonResponse($response);
                }

                if (!$controllerResponse instanceof ResponseInterface) {
                    throw new InvalidControllerException();
                }
                return $controllerResponse;
            };
    }
}
