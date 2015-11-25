<?php
namespace Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder;

use Sainsburys\HttpService\Components\ErrorHandling\ErrorControllerManager;
use Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sainsburys\HttpService\Components\Routing\Route;
use Psr\Http\Message\ResponseInterface;

class ErrorHandlingDecorator implements ControllerClosureBuilder
{
    /** @var ControllerClosureBuilder */
    private $thingBeingDecorated;

    /** @var ErrorControllerManager */
    private $errorControllerManager;

    /**
     * @param ControllerClosureBuilder $thingBeingDecorated
     * @param ErrorControllerManager   $errorControllerManager
     */
    public function __construct(
        ControllerClosureBuilder $thingBeingDecorated,
        ErrorControllerManager   $errorControllerManager
    ) {
        $this->thingBeingDecorated = $thingBeingDecorated;
        $this->errorControllerManager = $errorControllerManager;
    }

    /**
     * @param ContainerInterface $container
     * @param Route              $route
     * @return callable
     */
    public function buildControllerClosure(ContainerInterface $container, Route $route)
    {
        $rawControllerClosure = $this->thingBeingDecorated->buildControllerClosure($container, $route);
        $closureWhichAlsoDoesErrorHandling =
            $this->decorateWithErrorHandling($rawControllerClosure, $this->errorControllerManager);
        return $closureWhichAlsoDoesErrorHandling;
    }

    /**
     * @param callable               $rawControllerClosure
     * @param ErrorControllerManager $errorControllerManager
     * @return callable
     */
    private function decorateWithErrorHandling(
        callable               $rawControllerClosure,
        ErrorControllerManager $errorControllerManager
    ) {
        $errorController = $errorControllerManager->errorController();

        $controllerClosureWithErrorHandling =
            function (ServerRequestInterface $request, ResponseInterface $response) use (
                $rawControllerClosure, $errorController
            ) {
                try {
                    return $rawControllerClosure($request, $response);
                } catch (\Exception $exception) {
                    return $errorController->handleError($exception);
                }
            };

        return $controllerClosureWithErrorHandling;
    }
}
