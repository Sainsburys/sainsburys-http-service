<?php
namespace Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder;

use Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder;
use Sainsburys\HttpService\Components\ControllerClosures\Exception\InvalidControllerException;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sainsburys\HttpService\Components\Routing\Route;
use Psr\Http\Message\ResponseInterface;

class ResponseTypeDecorator implements ControllerClosureBuilder
{
    /** @var ControllerClosureBuilder */
    private $thingBeingDecorated;

    public function __construct(ControllerClosureBuilder $thingBeingDecorated)
    {
        $this->thingBeingDecorated = $thingBeingDecorated;
    }

    public function buildControllerClosure(ContainerInterface $container, Route $route): \Closure
    {
        $rawControllerClosure = $this->thingBeingDecorated->buildControllerClosure($container, $route);
        $closureWhichAlsoDoesTypeChecking = $this->decorateWithResponseTypeChecking($rawControllerClosure);
        return $closureWhichAlsoDoesTypeChecking;
    }

    private function decorateWithResponseTypeChecking(\Closure $rawControllerClosure): \Closure
    {
        $controllerClosureWithTypeChecking =
            function (ServerRequestInterface $request, ResponseInterface $response) use ($rawControllerClosure) {

                $response = $rawControllerClosure($request, $response);

                if (!$response instanceof ResponseInterface) {
                    throw new InvalidControllerException();
                }

                return $response;
            };

        return $controllerClosureWithTypeChecking;
    }
}
