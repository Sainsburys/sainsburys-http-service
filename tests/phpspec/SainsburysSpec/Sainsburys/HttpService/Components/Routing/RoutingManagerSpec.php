<?php
namespace SainsburysSpec\Sainsburys\HttpService\Components\Routing;

use Interop\Container\ContainerInterface;
use Sainsburys\HttpService\Components\Routing\Route;
use Sainsburys\HttpService\Components\Routing\RoutingConfigApplier;
use Sainsburys\HttpService\Components\Routing\RoutingConfigReader;
use Sainsburys\HttpService\Components\Routing\RoutingManager;
use Slim\App as SlimApp;
use Slim\Router as SlimRouter;
use Slim\Route as SlimRoute;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin RoutingManager
 */
class RoutingManagerSpec extends ObjectBehavior
{
    function let(RoutingConfigReader $routingConfigReader, RoutingConfigApplier $routingConfigApplier)
    {
        $this->beConstructedWith($routingConfigReader, $routingConfigApplier);
    }

    function it_is_initialisable()
    {
        $this->shouldHaveType('\Sainsburys\HttpService\Components\Routing\RoutingManager');
    }

    function it_can_configure_slim_app_with_routes(
        ContainerInterface   $container,
        SlimApp              $slimApp,
        Route                $route,
        RoutingConfigReader  $routingConfigReader,
        RoutingConfigApplier $routingConfigApplier
    ) {
        // ARRANGE
        $routingConfigReader->getRoutesFromFile('route-config.php')->willReturn([$route]);

        // ACT
        $this->configureSlimAppWithRoutes(['route-config.php'], $container, $slimApp);

        // ASSERT
        $routingConfigApplier
            ->configureApplicationWithRoutes($slimApp, [$route], $container)
            ->shouldHaveBeenCalled();
    }

    function it_can_tell_if_routes_have_been_configured(SlimRouter $slimRouter, SlimRoute $slimRoute)
    {
        // ARRANGE
        $slimApp = new SlimApp();
        $slimApp->getContainer()['router'] = $slimRouter;
        $slimRouter->getRoutes()->willReturn([$slimRoute]);

        // ACT
        $result = $this->someRoutesAreConfigured($slimApp);

        // ASSERT
        $result->shouldBe(true);
    }
}
