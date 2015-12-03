<?php
namespace Sainsburys\HttpService\Components\Routing;

use Interop\Container\ContainerInterface;
use Slim\App as SlimApp;
use Slim\Router as SlimRouter;

class RoutingManager
{
    /** @var RoutingConfigReader */
    private $routingConfigReader;

    /** @var RoutingConfigApplier */
    private $routingConfigApplier;

    /**
     * @param RoutingConfigReader  $routingConfigReader
     * @param RoutingConfigApplier $routingConfigApplier
     */
    public function __construct(
        RoutingConfigReader  $routingConfigReader,
        RoutingConfigApplier $routingConfigApplier
    ) {
        $this->routingConfigReader  = $routingConfigReader;
        $this->routingConfigApplier = $routingConfigApplier;
    }

    /**
     * @param string[]           $pathsToRoutingConfigs
     * @param ContainerInterface $containerWithControllers
     * @param SlimApp            $slimApp
     */
    public function configureSlimAppWithRoutes(
        array              $pathsToRoutingConfigs,
        ContainerInterface $containerWithControllers,
        SlimApp            $slimApp
    ) {
        foreach ($pathsToRoutingConfigs as $pathToRoutingConfig) {
            $routes = $this->routingConfigReader->getRoutesFromFile($pathToRoutingConfig);
            $this->routingConfigApplier->configureApplicationWithRoutes($slimApp, $routes, $containerWithControllers);
        }
    }

    /**
     * @param SlimApp $slimApp
     * @return bool
     */
    public function someRoutesAreConfigured(SlimApp $slimApp)
    {
        $slimRouter = $slimApp->getContainer()->get('router'); /** @var $slimRouter SlimRouter */
        return (bool)count($slimRouter->getRoutes());
    }
}
