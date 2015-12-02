<?php
namespace SainsburysSpec\Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder;

use Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder\MiddlewareDecorator;
use Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder;
use Sainsburys\HttpService\Components\Middlewares\Http\RequestAndResponse;
use Sainsburys\HttpService\Components\Middlewares\MiddlewareManager;
use Sainsburys\HttpService\Components\Routing\Route;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Interop\Container\ContainerInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * @mixin MiddlewareDecorator
 */
class MiddlewareDecoratorSpec extends ObjectBehavior
{
    function let(
        ControllerClosureBuilder $thingBeingDecorated,
        MiddlewareManager        $middlewareManager
    ) {
        $this->beConstructedWith($thingBeingDecorated, $middlewareManager);
    }

    function it_is_initialisable()
    {
        $this->shouldHaveType('Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder\MiddlewareDecorator');
    }

    function it_can_build_a_controller_closure(
        ServerRequestInterface   $initialRequest,
        ResponseInterface        $initialResponse,
        RequestAndResponse       $requestAndResponseBeforeUserController,
        ServerRequestInterface   $requestPassedToUserController,
        ResponseInterface        $responsePassedToUserController,
        ResponseInterface        $responseReturnedByUserController,
        ResponseInterface        $finalResponse,
        MiddlewareManager        $middlewareManager,
        ContainerInterface       $container,
        Route                    $route,
        ControllerClosureBuilder $thingBeingDecorated
    ) {
        // ARRANGE

        $middlewareManager
            ->applyBeforeMiddlewares(Argument::type('\Sainsburys\HttpService\Components\Middlewares\Http\RequestAndResponse'))
            ->willReturn($requestAndResponseBeforeUserController);

        $requestAndResponseBeforeUserController->request()->willReturn($requestPassedToUserController);
        $requestAndResponseBeforeUserController->response()->willReturn($responsePassedToUserController);

        $controllerClosureBeingDecorated =
            function ($requestPassedIn, $responsePassedIn) use (
                $responseReturnedByUserController
            ) {
                return new JsonResponse([]);
            };

        $middlewareManager->applyAfterMiddlewares(Argument::any())->willReturn($finalResponse);

        $thingBeingDecorated->buildControllerClosure($container, $route)->willReturn($controllerClosureBeingDecorated);

        // ACT
        $decoratedClosure = $this->buildControllerClosure($container, $route);
        $result = $decoratedClosure($initialRequest, $initialResponse);

        // ASSERT
        $result->shouldBe($finalResponse);
    }
}
