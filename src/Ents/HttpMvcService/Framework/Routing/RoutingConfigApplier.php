<?php
namespace Ents\HttpMvcService\Framework\Routing;

use Ents\HttpMvcService\Framework\Controller\ControllerClosureBuilder;
use Ents\HttpMvcService\Framework\Controller\ControllerClosureBuilderFactory;
use Ents\HttpMvcService\Framework\ErrorHandling\ErrorController;
use Ents\HttpMvcService\Framework\Exception\InvalidRouteConfigException;
use Pimple\Container;
use Slim\App as SlimApplication;

class RoutingConfigApplier
{
    /** @var ControllerClosureBuilderFactory */
    private $controllerClosureBuilderFactory;

    /**
     * @param ControllerClosureBuilderFactory $controllerClosureBuilderFactory
     */
    public function __construct(ControllerClosureBuilderFactory $controllerClosureBuilderFactory)
    {
        $this->controllerClosureBuilderFactory = $controllerClosureBuilderFactory;
    }

    /**
     * @param SlimApplication $slimApplication
     * @param Route           $route
     * @param Container       $container
     * @param ErrorController $errorController
     */
    public function configureApplicationWithRoute(
        SlimApplication $slimApplication,
        Route           $route,
        Container       $container,
        ErrorController $errorController
    ) {
        $this->validateControllerIsInDiConfig($route, $container);
        $controllerClosure = $this->buildControllerClosure($errorController, $container, $route);
        $this->applyRouteToSlimApplication($slimApplication, $route, $controllerClosure);
    }

    /**
     * @throws InvalidRouteConfigException
     *
     * @param Route     $route
     * @param Container $container
     */
    private function validateControllerIsInDiConfig(Route $route, Container $container)
    {
        if (!isset($container[$route->controllerServiceId()])) {
            throw new InvalidRouteConfigException(
                "Route " . $route->name() . "' requires controller service ID '" . $route->controllerServiceId() .
                "' - not found in DI config."
            );
        }
    }

    /**
     * @param ErrorController $errorController
     * @param Container       $container
     * @param Route           $route
     * @return callable
     */
    private function buildControllerClosure(ErrorController $errorController, Container $container, Route $route)
    {
        $controllerClosureBuilder = $this
            ->controllerClosureBuilderFactory
            ->getControllerClosureBuilder($errorController);

        return $controllerClosureBuilder->buildControllerClosure($container, $route);
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
