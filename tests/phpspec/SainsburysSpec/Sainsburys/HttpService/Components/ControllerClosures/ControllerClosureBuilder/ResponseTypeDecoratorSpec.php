<?php
namespace SainsburysSpec\Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder;

use Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder\ResponseTypeDecorator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder;
use Sainsburys\HttpService\Components\Routing\Route;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Interop\Container\ContainerInterface;

/**
 * @mixin ResponseTypeDecorator
 */
class ResponseTypeDecoratorSpec extends ObjectBehavior
{
    function let(ControllerClosureBuilder $thingBeingDecorated)
    {
        $this->beConstructedWith($thingBeingDecorated);
    }

    function it_is_initialisable()
    {
        $this->shouldHaveType('Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder\ResponseTypeDecorator');
    }

    function it_builds_a_closure_which_errors_if_response_object_not_returned(
        ContainerInterface       $container,
        Route                    $route,
        ControllerClosureBuilder $thingBeingDecorated,
        ServerRequestInterface   $request,
        ResponseInterface        $response
    ) {
        // ARRANGE

        $controllerClosureWithWrongReturnType =
            function ($requestPassedIn, $responsePassedIn) {
                return 123; // Not a ResponseInterface object
            };

        $thingBeingDecorated->buildControllerClosure($container, $route)->willReturn($controllerClosureWithWrongReturnType);

        // ACT
        $controllerClosureProduced = $this->buildControllerClosure($container, $route);

        // ASSERT
        $controllerClosureProduced
            ->shouldThrow('\Sainsburys\HttpService\Components\ControllerClosures\Exception\InvalidControllerException')
            ->during('__invoke', [$request, $response]);
    }
}
