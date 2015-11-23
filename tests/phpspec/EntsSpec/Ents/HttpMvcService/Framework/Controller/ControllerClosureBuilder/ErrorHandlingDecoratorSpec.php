<?php

namespace EntsSpec\Ents\HttpMvcService\Framework\Controller\ControllerClosureBuilder;

use Ents\HttpMvcService\Framework\Controller\ControllerClosureBuilder;
use Ents\HttpMvcService\Framework\ErrorHandling\ErrorController;
use Ents\HttpMvcService\Framework\Routing\Route;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Interop\Container\ContainerInterface;

class ErrorHandlingDecoratorSpec extends ObjectBehavior
{
    function let(ControllerClosureBuilder $thingBeingDecorated)
    {
        $this->beConstructedWith($thingBeingDecorated);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Ents\HttpMvcService\Framework\Controller\ControllerClosureBuilder\ErrorHandlingDecorator');
    }

    function it_can_build_a_controller_closure(
        ContainerInterface       $container,
        Route                    $route,
        ControllerClosureBuilder $thingBeingDecorated,
        ServerRequestInterface   $request,
        ResponseInterface        $response,
        ErrorController          $errorController,
        ResponseInterface        $errorControllerResponse
    ) {
        // ARRANGE

        $exceptionThrownByUserController = new \Exception();

        $controllerClosureWhichErrors =
            function ($requestPassedIn, $responsePassedIn) use ($exceptionThrownByUserController) {
                throw $exceptionThrownByUserController;
            };

        $thingBeingDecorated->buildControllerClosure($container, $route, $errorController)->willReturn($controllerClosureWhichErrors);

        $errorController->handleError($exceptionThrownByUserController)->willReturn($errorControllerResponse);

        // ACT
        $controllerClosure = $this->buildControllerClosure($container, $route, $errorController);
        $result = $controllerClosure($request, $response, []);

        // ASSERT
        $controllerClosure->shouldHaveType('closure');
        $result->shouldBe($errorControllerResponse);
    }
}
