<?php
namespace Sainsburys\HttpService;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Sainsburys\HttpService\Components\Logging\LoggingManager;
use Sainsburys\HttpService\Components\ErrorHandling\ErrorController\ErrorControllerManager;
use SamBurns\Pimple3ContainerInterop\ServiceContainer;
use Sainsburys\HttpService\Components\ErrorHandling\ErrorController\ErrorController;
use Sainsburys\HttpService\Components\Middlewares\MiddlewareManager;
use Sainsburys\HttpService\Components\Routing\RoutingConfigApplier;
use Interop\Container\ContainerInterface;
use Sainsburys\HttpService\Misc\DiConfig;
use Slim\App as SlimApplication;
use Sainsburys\HttpService\Components\Routing\RoutingConfigReader;

class Application implements LoggerAwareInterface
{
    /** @var SlimApplication */
    private $slimApplication;

    /** @var RoutingConfigReader */
    private $routingConfigReader;

    /** @var RoutingConfigApplier */
    private $routingConfigApplier;

    /** @var ErrorControllerManager */
    private $errorControllerManager;

    /** @var MiddlewareManager */
    private $middlewareManager;

    /** @var LoggingManager */
    private $loggingManager;

    /** @var ContainerInterface */
    private $container;

    /** @var bool */
    private $haveSomeRoutesConfigured = false;

    /**
     * @param SlimApplication        $slimApplication
     * @param RoutingConfigReader    $routingConfigReader
     * @param RoutingConfigApplier   $routingConfigApplier
     * @param ErrorControllerManager $errorControllerManager
     * @param MiddlewareManager      $middlewareManager
     * @param LoggingManager         $loggingManager
     */
    public function __construct(
        SlimApplication        $slimApplication,
        RoutingConfigReader    $routingConfigReader,
        RoutingConfigApplier   $routingConfigApplier,
        ErrorControllerManager $errorControllerManager,
        MiddlewareManager      $middlewareManager,
        LoggingManager         $loggingManager
    ) {
        $this->slimApplication        = $slimApplication;
        $this->routingConfigReader    = $routingConfigReader;
        $this->routingConfigApplier   = $routingConfigApplier;
        $this->errorControllerManager = $errorControllerManager;
        $this->middlewareManager      = $middlewareManager;
        $this->loggingManager         = $loggingManager;
    }

    /**
     * @param string[]           $routingConfigFiles
     * @param ContainerInterface $containerWithControllers
     * @return Application
     */
    public static function factory(array $routingConfigFiles, ContainerInterface $containerWithControllers)
    {
        $containerWithFramework = ServiceContainer::constructConfiguredWith(new DiConfig());
        $application = $containerWithFramework->get('sainsburys.sainsburys-http-service.application'); /** @var $application Application */

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
        $this->routingConfigApplier->configureApplicationWithRoutes($this->slimApplication, $routes, $this->container);
        $this->haveSomeRoutesConfigured = true;
    }

    /**
     * @param ErrorController $errorController
     */
    public function useThisErrorController(ErrorController $errorController)
    {
        $this->errorControllerManager->useThisErrorController($errorController);
    }

    /**
     * @deprecated  Use Application::setLogger()
     * @param LoggerInterface $logger
     */
    public function useThisLogger(LoggerInterface $logger)
    {
        $this->setLogger($logger);
    }

    /**
     * @param LoggerInterface $logger
     * @return null
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->loggingManager->setLogger($logger);
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
