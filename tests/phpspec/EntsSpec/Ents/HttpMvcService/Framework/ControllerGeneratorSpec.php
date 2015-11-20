<?php

namespace EntsSpec\Ents\HttpMvcService\Framework;

use Ents\HttpMvcService\Framework\Routing\Route;
use PhpSpec\ObjectBehavior;
use Pimple\Container;
use Prophecy\Argument;
use Slim\App as SlimApplication;

class ControllerGeneratorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Ents\HttpMvcService\Framework\ControllerGenerator');
    }

    function it_can_return_controller_callback(
        SlimApplication $slimApplication,
        Route           $route,
        Container       $container
    ) {
        // ARRANGE
        $route->controllerServiceId()->willReturn('controller-service-id');
        $route->actionMethodName()->willReturn('indexAction');
        $route->httpVerb()->willReturn('GET');
        $route->pathExpression()->willReturn('/person/:id');
        $route->name()->willReturn('route-name');

        // ACT
        $this->getControllerCallbackForRoute($route, $container, $slimApplication)->shouldHaveType('\Closure');
    }
}
