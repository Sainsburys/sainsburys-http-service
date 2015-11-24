<?php
namespace Sainsburys\HttpService\Framework;

use Sainsburys\HttpService\Di\FrameworkDiConfig;
use Sainsburys\HttpService\Framework\DiContainer\PimpleContainerInteropAdapter;
use Sainsburys\HttpService\Framework\ErrorHandling\ErrorController;
use Sainsburys\HttpService\Framework\Middleware\Manager\MiddlewareManager;
use Sainsburys\HttpService\Framework\Routing\RoutingConfigApplier;
use Interop\Container\ContainerInterface;
use Slim\App as SlimApplication;
use Sainsburys\HttpService\Framework\Routing\RoutingConfigReader;

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

    /** @var MiddlewareManager */
    private $middlewareManager;

    /** @var ContainerInterface */
    private $container;

    /** @var bool */
    private $haveSomeRoutesConfigured = false;

    /**
     * @param SlimApplication      $slimApplication
     * @param RoutingConfigReader  $routingConfigReader
     * @param RoutingConfigApplier $routingConfigApplier
     * @param ErrorController      $errorController
     * @param MiddlewareManager    $middlewareManager
     */
    public function __construct(
        SlimApplication      $slimApplication,
        RoutingConfigReader  $routingConfigReader,
        RoutingConfigApplier $routingConfigApplier,
        ErrorController      $errorController,
        MiddlewareManager    $middlewareManager
    ) {
        $this->slimApplication      = $slimApplication;
        $this->routingConfigReader  = $routingConfigReader;
        $this->routingConfigApplier = $routingConfigApplier;
        $this->errorController      = $errorController;
        $this->middlewareManager    = $middlewareManager;
    }

    /**
     * @param string[]           $routingConfigFiles
     * @param ContainerInterface $containerWithControllers
     * @return Application
     */
    public static function factory(array $routingConfigFiles, ContainerInterface $containerWithControllers)
    {
        $containerWithFramework = PimpleContainerInteropAdapter::constructConfiguredWith(new FrameworkDiConfig);
        $application = $containerWithFramework->get('ents.http-mvc-service.application'); /** @var $application Application */

        $application->takeContainerWithControllersConfigured($containerWithControllers);
        $application->takeRoutingConfigs($routingConfigFiles);

        return $application;
    }

    /**
     * @param ContainerInterface $container
     */
    public function takeContainerWithControllersConfigured(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string[] $paths
     */
    public function takeRoutingConfigs(array $paths)
    {
        foreach ($paths as $path) {
            $this->takeRoutingConfig($path);
        }
    }

    /**
     * @param string $path
     */
    private function takeRoutingConfig($path)
    {
        if (!$this->container) {
            throw new \RuntimeException('Must call takeContainerWithControllersConfigured() before takeRoutingConfig().  Try using Application::factory() to create the Application');
        }

        $routes = $this->routingConfigReader->getRoutesFromFile($path);
        $this->routingConfigApplier->configureApplicationWithRoutes($this->slimApplication, $routes, $this->container, $this->errorController);
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
     * @return MiddlewareManager
     */
    public function middlewareManager()
    {
        return $this->middlewareManager;
    }

    /**
     * @throws \RuntimeException
     */
    public function run()
    {
        if (!$this->haveSomeRoutesConfigured) {
            throw new \RuntimeException('Must call takeRoutingConfig() before run().  Try using Application::factory() to create the Application');
        }

        $this->slimApplication->run();
    }
}
