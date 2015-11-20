<?php
namespace EntsSpec\Ents\HttpMvcService\Framework\Routing;

use Ents\HttpMvcService\Framework\ControllerGenerator;
use Ents\HttpMvcService\Framework\Routing\Route;
use PhpSpec\ObjectBehavior;
use Pimple\Container;
use Prophecy\Argument;
use Slim\App as SlimApplication;
use Slim\Interfaces\RouteInterface as SlimRoute;

class RoutingConfigApplierSpec extends ObjectBehavior
{
    function let(ControllerGenerator $controllerGenerator)
    {
        $this->beConstructedWith($controllerGenerator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Ents\HttpMvcService\Framework\Routing\RoutingConfigApplier');
    }

    function it_can_configure_application_with_route(
        SlimApplication     $slimApplication,
        Route               $route,
        Container           $container,
        SlimRoute           $slimRoute,
        ControllerGenerator $controllerGenerator
    ) {
        $controllerCallback = function () {};

        $controllerGenerator
            ->getControllerCallbackForRoute($route, $container, $slimApplication)
            ->willReturn($controllerCallback);

        $slimApplication->map(['GET'], '/person/:id', $controllerCallback)->willReturn($slimRoute);

        /**
         * @todo Something's fucked with PHPSpec and the Symfony Console component, meaning this test passes, but
         *       printing the result crashes the test run.  Find out what the fuck is going on and fix it.
         */
//        $this->configureApplicationWithRoute($slimApplication, $route, $container)->shouldBe($controllerCallback);
    }
}
