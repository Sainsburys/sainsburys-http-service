<?php
namespace EntsSpec\Ents\HttpMvcService\Framework\Routing;

use Ents\HttpMvcService\Framework\Routing\Route;
use PhpSpec\ObjectBehavior;
use Pimple\Container;
use Prophecy\Argument;
use Slim\App as SlimApplication;
use Slim\Interfaces\RouteInterface as SlimRoute;

class RoutingConfigApplierSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Ents\HttpMvcService\Framework\Routing\RoutingConfigApplier');
    }

    function it_can_configure_application_with_route(
        SlimApplication $slimApplication,
        Route           $route,
        Container       $container,
        SlimRoute       $slimRoute
    ) {
        // ARRANGE
        $route->controllerServiceId()->willReturn('controller-service-id');
        $route->actionMethodName()->willReturn('indexAction');
        $route->httpVerb()->willReturn('GET');
        $route->pathExpression()->willReturn('/person/:id');
        $route->name()->willReturn('route-name');

        $slimApplication->map(['GET'], '/person/:id', Argument::type('callable'))->willReturn($slimRoute);

        // ACT
        $this->configureApplicationWithRoute($slimApplication, $route, $container);
    }
}
