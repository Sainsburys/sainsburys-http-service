<?php
namespace Sainsburys\HttpService;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
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

    public static function factory(array $routingConfigFiles, ContainerInterface $containerWithControllers): Application
    {
        $containerWithFramework = ServiceContainer::constructConfiguredWith(new DiConfig());
        $application = $containerWithFramework->get('sainsburys.sainsburys-http-service.application'); /** @var $application Application */

        $application->takeRoutingConfigs($routingConfigFiles, $containerWithControllers);

        return $application;
    }

    public function takeRoutingConfigs(array $paths, ContainerInterface $containerWithControllers)
    {
        $this->routingManager->configureSlimAppWithRoutes($paths, $containerWithControllers, $this->slimAppAdapter);
    }

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

    public function middlewareManager(): MiddlewareManager
    {
        return $this->middlewareManager;
    }

    public function run(ServerRequestInterface $testingRequest = null): ResponseInterface
    {
        return $this->slimAppAdapter->run($testingRequest);
    }
}
