<?php
namespace SainsburysSpec\Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder;

use Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder;
use Sainsburys\HttpService\Components\ErrorHandling\ErrorControllerManager;
use Sainsburys\HttpService\Components\ErrorHandling\ErrorController;
use Sainsburys\HttpService\Components\Routing\Route;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Interop\Container\ContainerInterface;

class ErrorHandlingDecoratorSpec extends ObjectBehavior
{
    function let(ControllerClosureBuilder $thingBeingDecorated, ErrorControllerManager $errorControllerManager)
    {
        $this->beConstructedWith($thingBeingDecorated, $errorControllerManager);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder\ErrorHandlingDecorator');
    }

    function it_can_build_a_controller_closure(
        ContainerInterface       $container,
        Route                    $route,
        ControllerClosureBuilder $thingBeingDecorated,
        ServerRequestInterface   $request,
        ResponseInterface        $response,
        ErrorController          $errorController,
        ErrorControllerManager   $errorControllerManager,
        ResponseInterface        $errorControllerResponse
    ) {
        // ARRANGE

        $exceptionThrownByUserController = new \Exception();

        $errorControllerManager->errorController()->willReturn($errorController);

        $controllerClosureWhichErrors =
            function ($requestPassedIn, $responsePassedIn) use ($exceptionThrownByUserController) {
                throw $exceptionThrownByUserController;
            };

        $thingBeingDecorated->buildControllerClosure($container, $route)->willReturn($controllerClosureWhichErrors);

        $errorController->handleError($exceptionThrownByUserController)->willReturn($errorControllerResponse);

        // ACT
        $controllerClosure = $this->buildControllerClosure($container, $route, $errorController);
        $result = $controllerClosure($request, $response);

        // ASSERT
        $controllerClosure->shouldHaveType('closure');
        $result->shouldBe($errorControllerResponse);
    }
}
