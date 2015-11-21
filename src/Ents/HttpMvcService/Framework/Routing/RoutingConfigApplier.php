<?php
namespace Ents\HttpMvcService\Framework\Routing;

use Ents\HttpMvcService\Framework\Controller\ControllerClosureBuilder;
use Ents\HttpMvcService\Framework\Controller\ControllerClosureBuilderFactory;
use Ents\HttpMvcService\Framework\ErrorHandling\ErrorController;
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
        $controllerClosureBuilder =
            $this->controllerClosureBuilderFactory->getControllerClosureBuilder($errorController);
        $controllerClosure = $controllerClosureBuilder->buildControllerClosure($container, $route);
        $this->applyRouteToSlimApplication($slimApplication, $route, $controllerClosure);
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
