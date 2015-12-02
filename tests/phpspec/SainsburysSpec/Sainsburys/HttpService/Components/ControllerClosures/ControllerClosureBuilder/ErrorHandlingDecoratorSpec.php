<?php
namespace SainsburysSpec\Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder;

use Psr\Log\LoggerInterface;
use Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder\ErrorHandlingDecorator;
use Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder;
use Sainsburys\HttpService\Components\ErrorHandling\ErrorController\ErrorControllerManager;
use Sainsburys\HttpService\Components\ErrorHandling\ErrorController\ErrorController;
use Sainsburys\HttpService\Components\Logging\LoggingManager;
use Sainsburys\HttpService\Components\Routing\Route;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Interop\Container\ContainerInterface;

/**
 * @mixin ErrorHandlingDecorator
 */
class ErrorHandlingDecoratorSpec extends ObjectBehavior
{
    function let(
        ControllerClosureBuilder $thingBeingDecorated,
        ErrorControllerManager   $errorControllerManager,
        LoggingManager           $loggingManager
    ) {
        $this->beConstructedWith($thingBeingDecorated, $errorControllerManager, $loggingManager);
    }

    function it_is_initialisable()
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
        ResponseInterface        $errorControllerResponse,
        LoggingManager           $loggingManager,
        LoggerInterface          $logger
    ) {
        // ARRANGE

        $exceptionThrownByUserController = new \Exception();

        $errorControllerManager->errorController()->willReturn($errorController);

        $loggingManager->logger()->willReturn($logger);

        $controllerClosureWhichErrors =
            function ($requestPassedIn, $responsePassedIn) use ($exceptionThrownByUserController) {
                throw $exceptionThrownByUserController;
            };

        $thingBeingDecorated->buildControllerClosure($container, $route)->willReturn($controllerClosureWhichErrors);

        $errorController->handleError($exceptionThrownByUserController, $logger)->willReturn($errorControllerResponse);

        // ACT
        $controllerClosure = $this->buildControllerClosure($container, $route);
        $result = $controllerClosure($request, $response);

        // ASSERT
        $controllerClosure->shouldHaveType('closure');
        $result->shouldBe($errorControllerResponse);
    }
}
