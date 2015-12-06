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

    public function __construct(
        ControllerClosureBuilder $thingBeingDecorated,
        ErrorControllerManager   $errorControllerManager,
        LoggingManager           $loggingManager
    ) {
        $this->thingBeingDecorated    = $thingBeingDecorated;
        $this->errorControllerManager = $errorControllerManager;
        $this->loggingManager         = $loggingManager;
    }

    public function buildControllerClosure(ContainerInterface $container, Route $route): \Closure
    {
        $rawControllerClosure = $this->thingBeingDecorated->buildControllerClosure($container, $route);
        $closureWhichAlsoDoesErrorHandling = $this->decorateWithErrorHandling(
            $rawControllerClosure,
            $this->errorControllerManager,
            $this->loggingManager
        );
        return $closureWhichAlsoDoesErrorHandling;
    }

    private function decorateWithErrorHandling(
        \Closure               $rawControllerClosure,
        ErrorControllerManager $errorControllerManager,
        LoggingManager         $loggingManager
    ): \Closure
    {
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
