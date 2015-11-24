<?php
namespace Sainsburys\HttpService\Framework\Controller\ControllerClosureBuilder;

use Sainsburys\HttpService\Framework\Controller\ControllerClosureBuilder;
use Sainsburys\HttpService\Framework\ErrorHandling\ErrorController;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sainsburys\HttpService\Framework\Routing\Route;
use Psr\Http\Message\ResponseInterface;

class CleanRequestAttributesDecorator implements ControllerClosureBuilder
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
        $closureWhichAlsoDoesErrorHandling = $this->decorateWithRequestCleaning($rawControllerClosure, $errorController);
        return $closureWhichAlsoDoesErrorHandling;
    }

    /**
     * @param callable        $rawControllerClosure
     * @param ErrorController $errorController
     * @return callable
     */
    private function decorateWithRequestCleaning(callable $rawControllerClosure, ErrorController $errorController)
    {
        $controllerClosureWithRequestCleaning =
            function (ServerRequestInterface $request, ResponseInterface $response) use (
                $rawControllerClosure, $errorController
            ) {
                $request = $request->withoutAttribute('route')->withoutAttribute('route-info');
                return $rawControllerClosure($request, $response);
            };

        return $controllerClosureWithRequestCleaning;
    }
}
