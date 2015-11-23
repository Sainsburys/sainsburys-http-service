<?php
namespace EntsSpec\Ents\HttpMvcService\Framework;

use Ents\HttpMvcService\Framework\Routing\Route;
use Interop\Container\ContainerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Ents\HttpMvcService\Framework\ErrorHandling\ErrorController;
use Ents\HttpMvcService\Framework\Routing\RoutingConfigApplier;
use Ents\HttpMvcService\Framework\Routing\RoutingConfigReader;
use Slim\App as SlimApplication;

class ApplicationSpec extends ObjectBehavior
{
    function let(
        SlimApplication      $slimApplication,
        RoutingConfigReader  $routingConfigReader,
        RoutingConfigApplier $routingConfigApplier,
        ErrorController      $errorController
    ) {
        $this->beConstructedWith($slimApplication, $routingConfigReader, $routingConfigApplier, $errorController);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Ents\HttpMvcService\Framework\Application');
    }

    function it_can_be_set_up_with_a_container(ContainerInterface $container)
    {
        $this->takeContainerWithControllersConfigured($container);
    }

    function it_can_be_set_up_with_a_container_and_routing_configs(
        ContainerInterface   $container,
        SlimApplication      $slimApplication,
        RoutingConfigReader  $routingConfigReader,
        RoutingConfigApplier $routingConfigApplier,
        ErrorController      $errorController,
        Route                $route
    ) {
        // ARRANGE
        $routingConfigReader->getRoutesFromFile('config/routes.php')->willReturn([$route]);

        // ACT
        $this->takeContainerWithControllersConfigured($container);
        $this->takeRoutingConfigs(['config/routes.php']);

        // ASSERT
        $routingConfigApplier
            ->configureApplicationWithRoutes($slimApplication, [$route], $container, $errorController)
            ->shouldHaveBeenCalled();
    }

    function it_can_run(
        ContainerInterface   $container,
        SlimApplication      $slimApplication,
        RoutingConfigReader  $routingConfigReader,
        Route                $route
    ) {

        // ARRANGE
        $routingConfigReader->getRoutesFromFile('config/routes.php')->willReturn([$route]);

        $this->takeContainerWithControllersConfigured($container);
        $this->takeRoutingConfigs(['config/routes.php']);

        $this->run();

        // ASSERT
        $slimApplication->run()->shouldHaveBeenCalled();
    }
}
