<?php
namespace Ents\HttpMvcService\Framework;

use Ents\HttpMvcService\Framework\Controller\ControllerClosureBuilderFactory;
use Ents\HttpMvcService\Framework\ErrorHandling\ErrorController;
use Ents\HttpMvcService\Framework\Routing\RoutingConfigApplier;
use Pimple\Container;
use Slim\App as SlimApplication;
use Ents\HttpMvcService\Framework\Routing\RoutingConfigReader;

class Application
{
    /** @var SlimApplication */
    private $slimApplication;

    /** @var RoutingConfigReader */
    private $routingConfigReader;

    /** @var RoutingConfigApplier */
    private $routingConfigApplier;

    /** @var ErrorController */
    private $errorController;

    /** @var Container */
    private $container;

    /** @var bool */
    private $haveSomeRoutesConfigured = false;

    /**
     * @param SlimApplication      $slimApplication
     * @param RoutingConfigReader  $routingConfigReader
     * @param RoutingConfigApplier $routingConfigApplier
     * @param ErrorController      $errorController
     */
    public function __construct(
        SlimApplication      $slimApplication,
        RoutingConfigReader  $routingConfigReader,
        RoutingConfigApplier $routingConfigApplier,
        ErrorController      $errorController
    ) {
        $this->slimApplication      = $slimApplication;
        $this->routingConfigReader  = $routingConfigReader;
        $this->routingConfigApplier = $routingConfigApplier;
        $this->errorController      = $errorController;
    }

    /**
     * @param Container $container
     */
    public function takeContainerWithControllersConfigured(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $path
     */
    public function takeRoutingConfig($path)
    {
        if (!$this->container) {
            throw new \RuntimeException('Must call takeContainerWithControllersConfigured() before takeRoutingConfig()');
        }

        $routes = $this->routingConfigReader->getRoutesFromFile($path);

        foreach ($routes as $route) {
            $this->routingConfigApplier->configureApplicationWithRoute(
                $this->slimApplication,
                $route,
                $this->container,
                $this->errorController
            );
        }

        $this->haveSomeRoutesConfigured = true;
    }

    /**
     * @param ErrorController $errorController
     */
    public function setErrorHandler(ErrorController $errorController)
    {
        $this->errorController = $errorController;
    }

    /**
     * @throws \RuntimeException
     */
    public function run()
    {
        if (!$this->haveSomeRoutesConfigured) {
            throw new \RuntimeException('Must call takeRoutingConfig() before run()');
        }

        $this->slimApplication->getContainer()->get('settings')['determineRouteBeforeAppMiddleware'] = true;
        $this->slimApplication->run();
    }
}
