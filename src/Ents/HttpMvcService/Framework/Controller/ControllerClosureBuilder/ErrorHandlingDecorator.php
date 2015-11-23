<?php
namespace Ents\HttpMvcService\Framework\Controller\ControllerClosureBuilder;

use Ents\HttpMvcService\Framework\Controller\ControllerClosureBuilder;
use Ents\HttpMvcService\Framework\ErrorHandling\ErrorController;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ents\HttpMvcService\Framework\Routing\Route;
use Psr\Http\Message\ResponseInterface;

class ErrorHandlingDecorator implements ControllerClosureBuilder
{
    /** @var ControllerClosureBuilder */
    private $thingBeingDecorated;

    /**
     * @param ControllerClosureBuilder $thingBeingDecorated
     */
    public function __construct(ControllerClosureBuilder $thingBeingDecorated)
    {
        $this->thingBeingDecorated = $thingBeingDecorated;

    }

    /**
     * @param ContainerInterface $container
     * @param Route              $route
     * @param ErrorController    $errorController
     * @return callable
     */
    public function buildControllerClosure(ContainerInterface $container, Route $route, ErrorController $errorController)
    {
        $rawControllerClosure = $this->thingBeingDecorated->buildControllerClosure($container, $route, $errorController);
        $closureWhichAlsoDoesErrorHandling = $this->decorateWithErrorHandling($rawControllerClosure, $errorController);
        return $closureWhichAlsoDoesErrorHandling;
    }

    /**
     * @param callable        $rawControllerClosure
     * @param ErrorController $errorController
     * @return callable
     */
    private function decorateWithErrorHandling(callable $rawControllerClosure, ErrorController $errorController)
    {
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
