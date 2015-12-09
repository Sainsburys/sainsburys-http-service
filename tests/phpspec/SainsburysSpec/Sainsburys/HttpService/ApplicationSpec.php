<?php
namespace SainsburysSpec\Sainsburys\HttpService;

use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Sainsburys\HttpService\Application;
use Sainsburys\HttpService\Components\Middlewares\MiddlewareManager;
use Interop\Container\ContainerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sainsburys\HttpService\Components\ErrorHandling\ErrorController\ErrorController;
use Sainsburys\HttpService\Components\Routing\RoutingManager;
use Sainsburys\HttpService\Components\SlimIntegration\SlimAppAdapter;

/**
 * @mixin Application
 */
class ApplicationSpec extends ObjectBehavior
{
    function let(
        SlimAppAdapter    $slimAppAdapter,
        RoutingManager    $routingManager,
        MiddlewareManager $middlewareManager,
        LoggerInterface   $logger,
        ErrorController   $errorController
    ) {
        $this->beConstructedWith(
            $slimAppAdapter,
            $routingManager,
            $middlewareManager,
            $logger,
            $errorController
        );
    }

    function it_is_initialisable()
    {
        $this->shouldHaveType('Sainsburys\HttpService\Application');
    }

    function it_can_be_set_up_with_a_container_and_routing_configs(
        ContainerInterface $container,
        SlimAppAdapter     $slimAppAdapter,
        RoutingManager     $routingManager
    ) {
        // ACT
        $this->takeRoutingConfigs(['config/routes.php'], $container);

        // ASSERT
        $routingManager
            ->configureSlimAppWithRoutes(['config/routes.php'], $container, $slimAppAdapter)
            ->shouldHaveBeenCalled();
    }

    function it_can_run(
        SlimAppAdapter    $slimAppAdapter,
        ResponseInterface $responseInterface,
        LoggerInterface   $logger,
        ErrorController   $errorController
    )
    {
        // ARRANGE
        $slimAppAdapter->run(null, $logger, $errorController)->willReturn($responseInterface);

        // ACT
        $this->run();

        // ASSERT
        $slimAppAdapter->run(null, $logger, $errorController)->shouldHaveBeenCalled();
    }

    function it_lets_you_put_another_error_controller(
        ErrorController   $newErrorController,
        SlimAppAdapter    $slimAppAdapter,
        LoggerInterface   $logger,
        ResponseInterface $response
    ) {
        // ARRANGE
        $slimAppAdapter->run(null, $logger, $newErrorController)->willReturn($response);

        // ACT
        $this->useThisErrorController($newErrorController);
        $this->run();

        // ASSERT
        $slimAppAdapter->run(null, $logger, $newErrorController)->shouldHaveBeenCalled();
    }

    function it_lets_you_put_another_logger(
        LoggerInterface   $newLogger,
        ErrorController   $errorController,
        SlimAppAdapter    $slimAppAdapter,
        ResponseInterface $response
    ) {
        // ARRANGE
        $slimAppAdapter->run(null, $newLogger, $errorController)->willReturn($response);

        // ACT
        $this->setLogger($newLogger);
        $this->run();

        // ASSERT
        $slimAppAdapter->run(null, $newLogger, $errorController)->shouldHaveBeenCalled();
    }

    function it_is_standards_compliant_wrt_psr3()
    {
        $this->shouldHaveType('Psr\Log\LoggerAwareInterface');
    }

    function it_can_return_the_middleware_manager(MiddlewareManager $middlewareManager)
    {
        $this->middlewareManager()->shouldBe($middlewareManager);
    }
}
