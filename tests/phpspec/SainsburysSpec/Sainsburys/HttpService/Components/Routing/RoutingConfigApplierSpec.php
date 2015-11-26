<?php

namespace SainsburysSpec\Sainsburys\HttpService\Components\Routing;

use Sainsburys\HttpService\Components\Routing\Route;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder;
use Sainsburys\HttpService\Components\ErrorHandling\ErrorController\ErrorController;
use Interop\Container\ContainerInterface;
use Slim\App as SlimApplication;
use Slim\Route as SlimRoute;

class RoutingConfigApplierSpec extends ObjectBehavior
{
    function let(ControllerClosureBuilder $controllerClosureBuilder)
    {
        $this->beConstructedWith($controllerClosureBuilder);
    }

    function it_is_initialisable()
    {
        $this->shouldHaveType('Sainsburys\HttpService\Components\Routing\RoutingConfigApplier');
    }

    function it_can_apply_routes_to_the_slim_application(
        ControllerClosureBuilder $controllerClosureBuilder,
        SlimApplication          $slimApplication,
        Route                    $route,
        ContainerInterface       $container,
        SlimRoute                $slimRoute
    ) {
        // ARRANGE

        // The controller closure builder builds the thing
        $controllerClosure = function () {};
        $controllerClosureBuilder->buildControllerClosure($container, $route)->willReturn($controllerClosure);

        // Route has stuff on it
        $route->controllerServiceId()->willReturn('controller-service-id');
        $route->httpVerb()->willReturn('GET');
        $route->name()->willReturn('route-name');
        $route->pathExpression()->willReturn('/path/');

        // Controller is in container
        $container->has('controller-service-id')->willReturn(true);

        // ASSERT

        // The Slim application must end up being configured
        $slimApplication->map(['GET'], '/path/', $controllerClosure)->willReturn($slimRoute);

        // The Slim route object must get a name
        $route->setName('route-name')->willReturn(null);

        // ACT
        $this->configureApplicationWithRoutes($slimApplication, [$route], $container);
    }

    function it_can_throw_an_exception_if_the_controller_isnt_in_the_container(
        ControllerClosureBuilder $controllerClosureBuilder,
        SlimApplication          $slimApplication,
        Route                    $route,
        ContainerInterface       $container,
        ErrorController          $errorController
    ) {
        // ARRANGE

        // The controller closure builder builds the thing
        $controllerClosure = function () {};
        $controllerClosureBuilder->buildControllerClosure($container, $route)->willReturn($controllerClosure);

        // Route has stuff on it
        $route->controllerServiceId()->willReturn('controller-service-id');
        $route->name()->willReturn('route-name');

        // Controller is not in container
        $container->has('controller-service-id')->willReturn(false);

        // ACT
        $this
            ->shouldThrow('\Sainsburys\HttpService\Components\Routing\Exception\InvalidRouteConfigException')
            ->during('configureApplicationWithRoutes', [$slimApplication, [$route], $container, $errorController]);
    }
}
