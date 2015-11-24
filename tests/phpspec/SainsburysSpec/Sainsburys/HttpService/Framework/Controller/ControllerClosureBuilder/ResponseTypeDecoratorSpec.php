<?php
namespace SainsburysSpec\Sainsburys\HttpService\Framework\Controller\ControllerClosureBuilder;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sainsburys\HttpService\Framework\Controller\ControllerClosureBuilder;
use Sainsburys\HttpService\Framework\ErrorHandling\ErrorController;
use Sainsburys\HttpService\Framework\Routing\Route;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Interop\Container\ContainerInterface;

class ResponseTypeDecoratorSpec extends ObjectBehavior
{
    function let(ControllerClosureBuilder $thingBeingDecorated)
    {
        $this->beConstructedWith($thingBeingDecorated);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sainsburys\HttpService\Framework\Controller\ControllerClosureBuilder\ResponseTypeDecorator');
    }

    function it_builds_a_closure_which_errors_if_response_object_not_returned(
        ContainerInterface       $container,
        Route                    $route,
        ControllerClosureBuilder $thingBeingDecorated,
        ServerRequestInterface   $request,
        ResponseInterface        $response,
        ErrorController          $errorController
    ) {
        // ARRANGE

        $controllerClosureWithWrongReturnType =
            function ($requestPassedIn, $responsePassedIn) {
                return 123; // Not a ResponseInterface object
            };

        $thingBeingDecorated->buildControllerClosure($container, $route, $errorController)->willReturn($controllerClosureWithWrongReturnType);

        // ACT
        $controllerClosureProduced = $this->buildControllerClosure($container, $route, $errorController);

        // ASSERT
        $controllerClosureProduced
            ->shouldThrow('\Sainsburys\HttpService\Framework\Exception\Framework\InvalidControllerException')
            ->during('__invoke', [$request, $response]);
    }
}
