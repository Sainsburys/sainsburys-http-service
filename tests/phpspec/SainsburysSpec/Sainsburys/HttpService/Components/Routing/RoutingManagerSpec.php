<?php
namespace SainsburysSpec\Sainsburys\HttpService\Components\Routing;

use Interop\Container\ContainerInterface;
use Sainsburys\HttpService\Components\Routing\Route;
use Sainsburys\HttpService\Components\Routing\RoutingConfigApplier;
use Sainsburys\HttpService\Components\Routing\RoutingConfigReader;
use Sainsburys\HttpService\Components\Routing\RoutingManager;
use Sainsburys\HttpService\Components\SlimIntegration\SlimAppAdapter;
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
        SlimAppAdapter       $slimAppAdapter,
        Route                $route,
        RoutingConfigReader  $routingConfigReader,
        RoutingConfigApplier $routingConfigApplier
    ) {
        // ARRANGE
        $routingConfigReader->getRoutesFromFile('route-config.php')->willReturn([$route]);

        // ACT
        $this->configureSlimAppWithRoutes(['route-config.php'], $container, $slimAppAdapter);

        // ASSERT
        $routingConfigApplier
            ->configureApplicationWithRoutes($slimAppAdapter, [$route], $container)
            ->shouldHaveBeenCalled();
    }
}
