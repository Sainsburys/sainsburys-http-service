<?php
namespace EntsSpec\Ents\HttpMvcService\Framework\Routing;

use Ents\HttpMvcService\Framework\Routing\Route;
use PhpSpec\ObjectBehavior;
use Pimple\Container;
use Prophecy\Argument;
use Slim\Slim as SlimApplication;

class RoutingConfigApplierSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Ents\HttpMvcService\Framework\Routing\RoutingConfigApplier');
    }

    function it_can_configure_application_with_route(
        SlimApplication $slimApplication,
        Route           $route,
        Container       $container
    ) {
        // ARRANGE
        $route->controllerServiceId()->willReturn('controller-service-id');
        $route->actionMethodName()->willReturn('indexAction');
        $route->httpVerb()->willReturn('GET');
        $route->pathExpression()->willReturn('/person/:id');

        // ACT
        $this->configureApplicationWithRoute($slimApplication, $route, $container);

        // ASSERT
        $slimApplication->get('/person/:id', Argument::type('callable'))->shouldHaveBeenCalled();
    }
}
