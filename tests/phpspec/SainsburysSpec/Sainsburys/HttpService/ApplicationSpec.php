<?php
namespace SainsburysSpec\Sainsburys\HttpService;

use Psr\Log\LoggerInterface;
use Sainsburys\HttpService\Application;
use Sainsburys\HttpService\Components\ErrorHandling\ErrorController\ErrorControllerManager;
use Sainsburys\HttpService\Components\Logging\LoggingManager;
use Sainsburys\HttpService\Components\Middlewares\MiddlewareManager;
use Sainsburys\HttpService\Components\Routing\Route;
use Interop\Container\ContainerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sainsburys\HttpService\Components\ErrorHandling\ErrorController\ErrorController;
use Sainsburys\HttpService\Components\Routing\RoutingManager;
use Slim\App as SlimApp;

/**
 * @mixin Application
 */
class ApplicationSpec extends ObjectBehavior
{
    function let(
        SlimApp                $slimApp,
        RoutingManager         $routingManager,
        ErrorControllerManager $errorControllerManager,
        MiddlewareManager      $middlewareManager,
        LoggingManager         $loggingManager
    ) {
        $this->beConstructedWith(
            $slimApp,
            $routingManager,
            $errorControllerManager,
            $middlewareManager,
            $loggingManager
        );
    }

    function it_is_initialisable()
    {
        $this->shouldHaveType('Sainsburys\HttpService\Application');
    }

    function it_can_be_set_up_with_a_container_and_routing_configs(
        ContainerInterface $container,
        SlimApp            $slimApp,
        RoutingManager     $routingManager
    ) {
        // ACT
        $this->takeRoutingConfigs(['config/routes.php'], $container);

        // ASSERT
        $routingManager
            ->configureSlimAppWithRoutes(['config/routes.php'], $container, $slimApp)
            ->shouldHaveBeenCalled();
    }

    function it_can_run(
        ContainerInterface $container,
        SlimApp            $slimApp,
        RoutingManager     $routingManager
    ) {
        // ARRANGE
        $routingManager->someRoutesAreConfigured($slimApp)->willReturn(true);

        // ACT
        $this->run();

        // ASSERT
        $slimApp->run()->shouldHaveBeenCalled();
    }

    function it_lets_you_put_another_error_controller(
        ErrorController        $newErrorController,
        ErrorControllerManager $errorControllerManager
    ) {
        // ACT
        $this->useThisErrorController($newErrorController);

        // ASSERT
        $errorControllerManager->useThisErrorController($newErrorController)->shouldHaveBeenCalled();
    }

    function it_lets_you_put_another_logger(
        LoggerInterface $logger,
        LoggingManager  $loggingManager
    ) {
        // ACT
        $this->setLogger($logger);

        // ASSERT
        $loggingManager->setLogger($logger)->shouldHaveBeenCalled();
    }

    function it_is_standards_compliant_wrt_psr3()
    {
        $this->shouldHaveType('Psr\Log\LoggerAwareInterface');
    }

    function it_can_return_the_middleware_manager(MiddlewareManager $middlewareManager)
    {
        $this->middlewareManager()->shouldBe($middlewareManager);
    }

    function it_cant_run_without_routes(RoutingManager $routingManager, SlimApp $slimApp)
    {
        $routingManager->someRoutesAreConfigured($slimApp)->willReturn(false);
        $this->shouldThrow('\RuntimeException')->during('run');
    }
}
