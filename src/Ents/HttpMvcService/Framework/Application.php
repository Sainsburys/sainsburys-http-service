<?php
namespace Ents\HttpMvcService\Framework;

use Ents\HttpMvcService\Di\FrameworkDiConfig;
use Ents\HttpMvcService\Framework\DiContainer\PimpleContainerInteropAdapter;
use Ents\HttpMvcService\Framework\ErrorHandling\ErrorController;
use Ents\HttpMvcService\Framework\Routing\RoutingConfigApplier;
use Interop\Container\ContainerInterface;
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

    /** @var ContainerInterface */
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
     * @throws \RuntimeException
     */
    public function run()
    {
        if (!$this->haveSomeRoutesConfigured) {
            throw new \RuntimeException('Must call takeRoutingConfig() before run().  Try using Application::factory() to create the Application');
        }

        $this->slimApplication->getContainer()->get('settings')['determineRouteBeforeAppMiddleware'] = true;
        $this->slimApplication->run();
    }
}
