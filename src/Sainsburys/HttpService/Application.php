<?php
namespace Sainsburys\HttpService;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Sainsburys\HttpService\Components\Logging\LoggingManager;
use Sainsburys\HttpService\Components\ErrorHandling\ErrorController\ErrorControllerManager;
use SamBurns\Pimple3ContainerInterop\ServiceContainer;
use Sainsburys\HttpService\Components\ErrorHandling\ErrorController\ErrorController;
use Sainsburys\HttpService\Components\Middlewares\MiddlewareManager;
use Interop\Container\ContainerInterface;
use Sainsburys\HttpService\Misc\DiConfig;
use Sainsburys\HttpService\Components\SlimIntegration\SlimAppAdapter;
use Sainsburys\HttpService\Components\Routing\RoutingManager;

class Application implements LoggerAwareInterface
{
    /** @var SlimAppAdapter */
    private $slimAppAdapter;

    /** @var RoutingManager */
    private $routingManager;

    /** @var ErrorControllerManager */
    private $errorControllerManager;

    /** @var MiddlewareManager */
    private $middlewareManager;

    /** @var LoggingManager */
    private $loggingManager;

    /**
     * @param SlimAppAdapter         $slimAppAdapter
     * @param RoutingManager         $routingManager
     * @param ErrorControllerManager $errorControllerManager
     * @param MiddlewareManager      $middlewareManager
     * @param LoggingManager         $loggingManager
     */
    public function __construct(
        SlimAppAdapter         $slimAppAdapter,
        RoutingManager         $routingManager,
        ErrorControllerManager $errorControllerManager,
        MiddlewareManager      $middlewareManager,
        LoggingManager         $loggingManager
    ) {
        $this->slimAppAdapter         = $slimAppAdapter;
        $this->routingManager         = $routingManager;
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

        $application->takeRoutingConfigs($routingConfigFiles, $containerWithControllers);

        return $application;
    }

    /**
     * @param string[]           $paths
     * @param ContainerInterface $containerWithControllers
     */
    public function takeRoutingConfigs(array $paths, ContainerInterface $containerWithControllers)
    {
        $this->routingManager->configureSlimAppWithRoutes($paths, $containerWithControllers, $this->slimAppAdapter);
    }

    /**
     * @param ErrorController $errorController
     */
    public function useThisErrorController(ErrorController $errorController)
    {
        $this->errorControllerManager->useThisErrorController($errorController);
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
     * @param ServerRequestInterface|null $testingRequest
     */
    public function run(ServerRequestInterface $testingRequest = null)
    {
        $this->slimAppAdapter->run($testingRequest);
    }
}
