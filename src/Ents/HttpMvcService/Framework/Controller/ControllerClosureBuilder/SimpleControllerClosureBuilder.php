<?php
namespace Ents\HttpMvcService\Framework\Controller\ControllerClosureBuilder;

use Ents\HttpMvcService\Framework\Controller\ControllerClosureBuilder;
use Ents\HttpMvcService\Framework\Routing\Route;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class SimpleControllerClosureBuilder implements ControllerClosureBuilder
{
    /**
     * @param ContainerInterface $container
     * @param Route              $route
     * @return callable
     */
    public function buildControllerClosure(ContainerInterface $container, Route $route)
    {
        $controllerServiceId = $route->controllerServiceId();
        $actionMethodName    = $route->actionMethodName();

        $controllerClosure =
            function (RequestInterface $request, ResponseInterface $response, array $urlVars) use (
                $container, $controllerServiceId, $actionMethodName
            ) {
                $controllerObject   = $container->get($controllerServiceId);
                $controllerResponse = $controllerObject->$actionMethodName($request, $response, $urlVars);
                return $controllerResponse;
            };

        return $controllerClosure;
    }
}
