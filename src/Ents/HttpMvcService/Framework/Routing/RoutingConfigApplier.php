<?php
namespace Ents\HttpMvcService\Framework\Routing;

use Ents\HttpMvcService\Framework\ControllerGenerator;
use Pimple\Container;
use Slim\App as SlimApplication;

class RoutingConfigApplier
{
    /** @var ControllerGenerator */
    private $controllerGenerator;

    /**
     * @param ControllerGenerator $controllerGenerator
     */
    public function __construct(ControllerGenerator $controllerGenerator)
    {
        $this->controllerGenerator = $controllerGenerator;
    }

    /**
     * @param SlimApplication $slimApplication
     * @param Route           $route
     * @param Container       $container
     */
    public function configureApplicationWithRoute(SlimApplication $slimApplication, Route $route, Container $container)
    {
        $controllerCallback = $this->getControllerCallbackForRoute($slimApplication, $route, $container);
        $this->applyRouteToSlimApplication($slimApplication, $route, $controllerCallback);
    }

    /**
     * @param SlimApplication $slimApplication
     * @param Route           $route
     * @param Container       $container
     * @return callable
     */
    private function getControllerCallbackForRoute(SlimApplication $slimApplication, Route $route, Container $container)
    {
        return $this->controllerGenerator->getControllerCallbackForRoute($route, $container, $slimApplication);
    }

    /**
     * @param SlimApplication $slimApplication
     * @param Route           $route
     * @param callable        $controllerCallback
     */
    private function applyRouteToSlimApplication(
        SlimApplication $slimApplication,
        Route $route,
        callable $controllerCallback
    ) {
        $slimApplication
            ->map(
                [$route->httpVerb()],
                $route->pathExpression(),
                $controllerCallback
            )
            ->setName($route->name())
        ;
    }
}
