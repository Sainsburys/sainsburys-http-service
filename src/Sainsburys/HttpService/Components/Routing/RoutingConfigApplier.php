<?php
namespace Sainsburys\HttpService\Components\Routing;

use Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder;
use Sainsburys\HttpService\Components\Routing\Exception\InvalidRouteConfigException;
use Interop\Container\ContainerInterface;
use Sainsburys\HttpService\Components\SlimIntegration\SlimAppAdapter;

class RoutingConfigApplier
{
    /** @var ControllerClosureBuilder */
    private $controllerClosureBuilder;

    public function __construct(ControllerClosureBuilder $controllerClosureBuilder)
    {
        $this->controllerClosureBuilder = $controllerClosureBuilder;
    }

    /**
     * @param Route[] $routes
     */
    public function configureApplicationWithRoutes(
        SlimAppAdapter     $slimAppAdapter,
        array              $routes,
        ContainerInterface $container
    ) {
        foreach ($routes as $route) {
            $this->configureApplicationWithRoute($slimAppAdapter, $route, $container);
        }
    }

    private function configureApplicationWithRoute(
        SlimAppAdapter     $slimAppAdapter,
        Route              $route,
        ContainerInterface $container
    ) {
        $this->validateControllerIsInDiConfig($route, $container);
        $controllerClosure = $this->controllerClosureBuilder->buildControllerClosure($container, $route);
        $this->applyRouteToSlimApplication($slimAppAdapter, $route, $controllerClosure);
    }

    /**
     * @throws InvalidRouteConfigException
     */
    private function validateControllerIsInDiConfig(Route $route, ContainerInterface $container)
    {
        if (!$container->has($route->controllerServiceId())) {
            throw new InvalidRouteConfigException(
                "Route " . $route->name() . "' requires controller service ID '" . $route->controllerServiceId() .
                "' - not found in DI config."
            );
        }
    }

    private function applyRouteToSlimApplication(
        SlimAppAdapter     $slimAppAdapter,
        Route           $route,
        \Closure        $controllerClosure
    ) {
        $slimAppAdapter->addRoute($route, $controllerClosure);
    }
}
