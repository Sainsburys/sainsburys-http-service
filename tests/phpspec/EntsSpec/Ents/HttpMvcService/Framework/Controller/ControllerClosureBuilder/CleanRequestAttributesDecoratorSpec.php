<?php
namespace EntsSpec\Ents\HttpMvcService\Framework\Controller\ControllerClosureBuilder;

use Ents\HttpMvcService\Framework\Controller\ControllerClosureBuilder;
use Ents\HttpMvcService\Framework\ErrorHandling\ErrorController;
use Ents\HttpMvcService\Framework\Routing\Route;
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
        $this->shouldHaveType('\Ents\HttpMvcService\Framework\Controller\ControllerClosureBuilder\CleanRequestAttributesDecorator');
    }

    function it_can_build_a_controller_closure(
        ContainerInterface       $container,
        Route                    $route,
        ControllerClosureBuilder $thingBeingDecorated,
        ServerRequestInterface   $originalRequest,
        ServerRequestInterface   $requestWithLessRubbish,
        ServerRequestInterface   $requestWithNoRubbish,
        ResponseInterface        $response,
        ErrorController          $errorController
    ) {
        // ARRANGE

        $originalControllerClosure =
            function ($originalRequest, $responsePassedIn) {
                return $responsePassedIn;
            };

        $originalRequest->withoutAttribute('route')->willReturn($requestWithLessRubbish);
        $requestWithLessRubbish->withoutAttribute('route-info')->willReturn($requestWithNoRubbish);

        $thingBeingDecorated->buildControllerClosure($container, $route, $errorController)->willReturn($originalControllerClosure);

        // ACT
        $controllerClosure = $this->buildControllerClosure($container, $route, $errorController);
        $result = $controllerClosure($originalRequest, $response);

        // ASSERT
        $controllerClosure->shouldHaveType('closure');
        $result->shouldBe($response);
    }
}
