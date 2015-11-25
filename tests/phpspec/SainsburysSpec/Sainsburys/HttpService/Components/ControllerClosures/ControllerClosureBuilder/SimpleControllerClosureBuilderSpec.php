<?php
namespace SainsburysSpec\Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder;

use Sainsburys\HttpService\Dev\Controller\SimpleController;
use Sainsburys\HttpService\Components\Routing\Route;
use PhpSpec\ObjectBehavior;
use Interop\Container\ContainerInterface;
use Prophecy\Argument;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

class SimpleControllerClosureBuilderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder\SimpleControllerClosureBuilder');
    }

    function it_can_build_a_controller_closure(
        ContainerInterface     $container,
        Route                  $route,
        SimpleController       $simpleController,
        ServerRequestInterface $request,
        ResponseInterface      $response,
        JsonResponse           $jsonResponse
    ) {
        // ARRANGE
        $route->controllerServiceId()->willReturn('controller-service-id');
        $route->actionMethodName()->willReturn('simpleAction');

        $container->get('controller-service-id')->willReturn($simpleController);

        $simpleController->simpleAction($request, $response)->willReturn($jsonResponse);

        // ACT
        $controllerClosure = $this->buildControllerClosure($container, $route);
        $result = $controllerClosure($request, $response);

        // ASSERT
        $controllerClosure->shouldHaveType('closure');
        $result->shouldBe($jsonResponse);
    }
}
