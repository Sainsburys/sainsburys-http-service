<?php
namespace Sainsburys\HttpService\Components\Routing;

use Interop\Container\ContainerInterface;
use Sainsburys\HttpService\Components\SlimIntegration\SlimAppAdapter;

class RoutingManager
{
    /** @var RoutingConfigReader */
    private $routingConfigReader;

    /** @var RoutingConfigApplier */
    private $routingConfigApplier;

    public function __construct(
        RoutingConfigReader  $routingConfigReader,
        RoutingConfigApplier $routingConfigApplier
    ) {
        $this->routingConfigReader  = $routingConfigReader;
        $this->routingConfigApplier = $routingConfigApplier;
    }

    /**
     * @param string[] $pathsToRoutingConfigs
     */
    public function configureSlimAppWithRoutes(
        array              $pathsToRoutingConfigs,
        ContainerInterface $containerWithControllers,
        SlimAppAdapter     $slimAppAdapter
    ) {
        foreach ($pathsToRoutingConfigs as $pathToRoutingConfig) {
            $routes = $this->routingConfigReader->getRoutesFromFile($pathToRoutingConfig);
            $this->routingConfigApplier->configureApplicationWithRoutes($slimAppAdapter, $routes, $containerWithControllers);
        }
    }
}
