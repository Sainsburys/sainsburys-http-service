<?php
namespace Sainsburys\HttpService;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Sainsburys\HttpService\Components\ErrorHandling\ErrorController\ErrorController;
use Sainsburys\HttpService\Components\Middlewares\MiddlewareManager;
use Interop\Container\ContainerInterface;
use Sainsburys\HttpService\Components\SlimIntegration\SlimAppAdapter;
use Sainsburys\HttpService\Components\Routing\RoutingManager;
use UltraLite\Container\Container;

class Application implements LoggerAwareInterface
{
    /** @var SlimAppAdapter */
    private $slimAppAdapter;

    /** @var RoutingManager */
    private $routingManager;

    /** @var ErrorController */
    private $errorController;

    /** @var MiddlewareManager */
    private $middlewareManager;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        SlimAppAdapter    $slimAppAdapter,
        RoutingManager    $routingManager,
        MiddlewareManager $middlewareManager,
        LoggerInterface   $logger,
        ErrorController   $errorController
    ) {
        $this->slimAppAdapter    = $slimAppAdapter;
        $this->routingManager    = $routingManager;
        $this->middlewareManager = $middlewareManager;
        $this->logger            = $logger;
        $this->errorController   = $errorController;
    }

    public static function factory(array $routingConfigFiles, ContainerInterface $containerWithControllers): Application
    {
        $containerWithFramework = new Container();
        $containerWithFramework->configureFromFile(__DIR__ . '/../../../config/di.php');

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
        $this->errorController = $errorController;
    }

    /**
     * @param LoggerInterface $logger
     * @return null
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function middlewareManager(): MiddlewareManager
    {
        return $this->middlewareManager;
    }

    public function run(ServerRequestInterface $testingRequest = null): ResponseInterface
    {
        return $this->slimAppAdapter->run($testingRequest, $this->logger, $this->errorController);
    }
}
