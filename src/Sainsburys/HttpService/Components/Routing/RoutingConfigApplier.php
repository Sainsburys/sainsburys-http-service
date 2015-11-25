<?php
namespace Sainsburys\HttpService\Components\Routing;

use Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder;
use Sainsburys\HttpService\Components\Routing\Exception\InvalidRouteConfigException;
use Interop\Container\ContainerInterface;
use Slim\App as SlimApplication;

class RoutingConfigApplier
{
    /** @var ControllerClosureBuilder */
    private $controllerClosureBuilder;

    /**
     * @param ControllerClosureBuilder $controllerClosureBuilder
     */
    public function __construct(ControllerClosureBuilder $controllerClosureBuilder)
    {
        $this->controllerClosureBuilder = $controllerClosureBuilder;
    }

    /**
     * @param SlimApplication    $slimApplication
     * @param Route[]            $routes
     * @param ContainerInterface $container
     */
    public function configureApplicationWithRoutes(
        SlimApplication    $slimApplication,
        array              $routes,
        ContainerInterface $container
    ) {
        foreach ($routes as $route) {
            $this->configureApplicationWithRoute($slimApplication, $route, $container);
        }
    }

    /**
     * @param SlimApplication    $slimApplication
     * @param Route              $route
     * @param ContainerInterface $container
     */
    private function configureApplicationWithRoute(
        SlimApplication    $slimApplication,
        Route              $route,
        ContainerInterface $container
    ) {
        $this->validateControllerIsInDiConfig($route, $container);
        $controllerClosure = $this->controllerClosureBuilder->buildControllerClosure($container, $route);
        $this->applyRouteToSlimApplication($slimApplication, $route, $controllerClosure);
    }

    /**
     * @throws InvalidRouteConfigException
     *
     * @param Route              $route
     * @param ContainerInterface $container
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

    /**
     * @param SlimApplication $slimApplication
     * @param Route           $route
     * @param callable        $controllerClosure
     */
    private function applyRouteToSlimApplication(
        SlimApplication $slimApplication,
        Route           $route,
        callable        $controllerClosure
    ) {
        $slimApplication
            ->map(
                [$route->httpVerb()],
                $route->pathExpression(),
                $controllerClosure
            )
            ->setName($route->name())
        ;
    }
}
