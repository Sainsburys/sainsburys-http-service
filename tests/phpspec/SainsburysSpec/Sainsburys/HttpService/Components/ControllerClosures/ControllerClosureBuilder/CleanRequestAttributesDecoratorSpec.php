<?php
namespace SainsburysSpec\Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder;

use Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder;
use Sainsburys\HttpService\Components\Routing\Route;
use Interop\Container\ContainerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CleanRequestAttributesDecoratorSpec extends ObjectBehavior
{
    function let(ControllerClosureBuilder $thingBeingDecorated)
    {
        $this->beConstructedWith($thingBeingDecorated);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('\Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder\CleanRequestAttributesDecorator');
    }

    function it_can_build_a_controller_closure(
        ContainerInterface       $container,
        Route                    $route,
        ControllerClosureBuilder $thingBeingDecorated,
        ServerRequestInterface   $originalRequest,
        ServerRequestInterface   $requestWithLessRubbish,
        ServerRequestInterface   $requestWithNoRubbish,
        ResponseInterface        $response
    ) {
        // ARRANGE

        $originalControllerClosure =
            function ($originalRequest, $responsePassedIn) {
                return $responsePassedIn;
            };

        $originalRequest->withoutAttribute('route')->willReturn($requestWithLessRubbish);
        $requestWithLessRubbish->withoutAttribute('route-info')->willReturn($requestWithNoRubbish);

        $thingBeingDecorated->buildControllerClosure($container, $route)->willReturn($originalControllerClosure);

        // ACT
        $controllerClosure = $this->buildControllerClosure($container, $route);
        $result = $controllerClosure($originalRequest, $response);

        // ASSERT
        $controllerClosure->shouldHaveType('closure');
        $result->shouldBe($response);
    }
}
