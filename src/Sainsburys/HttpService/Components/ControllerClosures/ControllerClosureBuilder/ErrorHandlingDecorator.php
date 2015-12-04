<?php
namespace Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder;

use Sainsburys\HttpService\Components\ErrorHandling\ErrorController\ErrorControllerManager;
use Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sainsburys\HttpService\Components\Logging\LoggingManager;
use Sainsburys\HttpService\Components\Routing\Route;
use Psr\Http\Message\ResponseInterface;

class ErrorHandlingDecorator implements ControllerClosureBuilder
{
    /** @var ControllerClosureBuilder */
    private $thingBeingDecorated;

    /** @var ErrorControllerManager */
    private $errorControllerManager;

    /** @var LoggingManager */
    private $loggingManager;

    /**
     * @param ControllerClosureBuilder $thingBeingDecorated
     * @param ErrorControllerManager   $errorControllerManager
     * @param LoggingManager           $loggingManager
     */
    public function __construct(
        ControllerClosureBuilder $thingBeingDecorated,
        ErrorControllerManager   $errorControllerManager,
        LoggingManager           $loggingManager
    ) {
        $this->thingBeingDecorated    = $thingBeingDecorated;
        $this->errorControllerManager = $errorControllerManager;
        $this->loggingManager         = $loggingManager;
    }

    /**
     * @param ContainerInterface $container
     * @param Route              $route
     * @return \Closure
     */
    public function buildControllerClosure(ContainerInterface $container, Route $route)
    {
        $rawControllerClosure = $this->thingBeingDecorated->buildControllerClosure($container, $route);
        $closureWhichAlsoDoesErrorHandling = $this->decorateWithErrorHandling(
            $rawControllerClosure,
            $this->errorControllerManager,
            $this->loggingManager
        );
        return $closureWhichAlsoDoesErrorHandling;
    }

    /**
     * @param \Closure               $rawControllerClosure
     * @param ErrorControllerManager $errorControllerManager
     * @param LoggingManager         $loggingManager
     * @return \Closure
     */
    private function decorateWithErrorHandling(
        \Closure               $rawControllerClosure,
        ErrorControllerManager $errorControllerManager,
        LoggingManager         $loggingManager
    ) {
        $errorController = $errorControllerManager->errorController();
        $logger = $loggingManager->logger();

        $controllerClosureWithErrorHandling =
            function (ServerRequestInterface $request, ResponseInterface $response) use (
                $rawControllerClosure, $errorController, $logger
            ) {
                try {
                    return $rawControllerClosure($request, $response);
                } catch (\Exception $exception) {
                    return $errorController->handleError($exception, $logger);
                }
            };

        return $controllerClosureWithErrorHandling;
    }
}
