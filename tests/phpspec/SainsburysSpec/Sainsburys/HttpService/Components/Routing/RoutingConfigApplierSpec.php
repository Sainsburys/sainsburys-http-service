<?php
namespace SainsburysSpec\Sainsburys\HttpService\Components\Routing;

use Sainsburys\HttpService\Components\Routing\RoutingConfigApplier;
use Sainsburys\HttpService\Components\Routing\Route;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder;
use Sainsburys\HttpService\Components\ErrorHandling\ErrorController\ErrorController;
use Interop\Container\ContainerInterface;
use Sainsburys\HttpService\Components\SlimIntegration\SlimAppAdapter;
use Slim\Route as SlimRoute;

/**
 * @mixin RoutingConfigApplier
 */
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
        SlimAppAdapter           $slimAppAdapter,
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

        // ACT
        $this->configureApplicationWithRoutes($slimAppAdapter, [$route], $container);

        // ASSERT
        $slimAppAdapter->addRoute($route, $controllerClosure)->shouldHaveBeenCalled();
    }

    function it_can_throw_an_exception_if_the_controller_isnt_in_the_container(
        ControllerClosureBuilder $controllerClosureBuilder,
        SlimAppAdapter           $slimAppAdapter,
        Route                    $route,
        ContainerInterface       $container,
        ErrorController          $errorController
    ) {
        // ARRANGE

        // Route has stuff on it
        $route->controllerServiceId()->willReturn('controller-service-id');
        $route->name()->willReturn('route-name');

        // Controller is not in container
        $container->has('controller-service-id')->willReturn(false);

        // ACT/ASSERT
        $this
            ->shouldThrow('\Sainsburys\HttpService\Components\Routing\Exception\InvalidRouteConfigException')
            ->during('configureApplicationWithRoutes', [$slimAppAdapter, [$route], $container, $errorController]);
    }
}
