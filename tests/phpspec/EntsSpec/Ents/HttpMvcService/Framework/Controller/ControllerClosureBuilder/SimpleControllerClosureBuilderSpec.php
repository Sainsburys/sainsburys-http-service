<?php

namespace EntsSpec\Ents\HttpMvcService\Framework\Controller\ControllerClosureBuilder;

use Ents\HttpMvcService\Dev\Controller\SimpleController;
use Ents\HttpMvcService\Framework\ErrorHandling\ErrorController;
use Ents\HttpMvcService\Framework\Routing\Route;
use PhpSpec\ObjectBehavior;
use Interop\Container\ContainerInterface;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

class SimpleControllerClosureBuilderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Ents\HttpMvcService\Framework\Controller\ControllerClosureBuilder\SimpleControllerClosureBuilder');
    }

    function it_can_build_a_controller_closure(
        ContainerInterface $container,
        Route              $route,
        SimpleController   $simpleController,
        RequestInterface   $request,
        ResponseInterface  $response,
        JsonResponse       $jsonResponse,
        ErrorController    $errorController
    ) {
        // ARRANGE
        $route->controllerServiceId()->willReturn('controller-service-id');
        $route->actionMethodName()->willReturn('simpleAction');

        $container->get('controller-service-id')->willReturn($simpleController);

        $simpleController->simpleAction($request, $response, [])->willReturn($jsonResponse);

        // ACT
        $controllerClosure = $this->buildControllerClosure($container, $route, $errorController);
        $result = $controllerClosure($request, $response, []);

        // ASSERT
        $controllerClosure->shouldHaveType('closure');
        $result->shouldBe($jsonResponse);
    }
}
