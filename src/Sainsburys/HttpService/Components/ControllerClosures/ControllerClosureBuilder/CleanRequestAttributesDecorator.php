<?php
namespace Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder;

use Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sainsburys\HttpService\Components\Routing\Route;
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
     * @return callable
     */
    public function buildControllerClosure(ContainerInterface $container, Route $route)
    {
        $rawControllerClosure = $this->thingBeingDecorated->buildControllerClosure($container, $route);
        $closureWhichAlsoDoesErrorHandling = $this->decorateWithRequestCleaning($rawControllerClosure);
        return $closureWhichAlsoDoesErrorHandling;
    }

    /**
     * @param callable        $rawControllerClosure
     * @return callable
     */
    private function decorateWithRequestCleaning(callable $rawControllerClosure)
    {
        $controllerClosureWithRequestCleaning =
            function (ServerRequestInterface $request, ResponseInterface $response) use ($rawControllerClosure) {
                $request = $request->withoutAttribute('route')->withoutAttribute('route-info');
                return $rawControllerClosure($request, $response);
            };

        return $controllerClosureWithRequestCleaning;
    }
}
