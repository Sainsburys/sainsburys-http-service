<?php
namespace Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder;

use Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder;
use Sainsburys\HttpService\Components\Routing\Route;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class SimpleControllerClosureBuilder implements ControllerClosureBuilder
{
    public function buildControllerClosure(ContainerInterface $container, Route $route): \Closure
    {
        $controllerServiceId = $route->controllerServiceId();
        $actionMethodName    = $route->actionMethodName();

        $controllerClosure =
            function (ServerRequestInterface $request, ResponseInterface $response) use (
                $container, $controllerServiceId, $actionMethodName
            ) {
                $controllerObject   = $container->get($controllerServiceId);
                $controllerResponse = $controllerObject->$actionMethodName($request, $response);
                return $controllerResponse;
            };

        return $controllerClosure;
    }
}
