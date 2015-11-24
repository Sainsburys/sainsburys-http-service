<?php
namespace Sainsburys\HttpService\Framework\Controller\ControllerClosureBuilder;

use Sainsburys\HttpService\Framework\Controller\ControllerClosureBuilder;
use Sainsburys\HttpService\Framework\ErrorHandling\ErrorController;
use Sainsburys\HttpService\Framework\Exception\Framework\InvalidControllerException;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sainsburys\HttpService\Framework\Routing\Route;
use Psr\Http\Message\ResponseInterface;

class ResponseTypeDecorator implements ControllerClosureBuilder
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
        $closureWhichAlsoDoesTypeChecking = $this->decorateWithResponseTypeChecking($rawControllerClosure);
        return $closureWhichAlsoDoesTypeChecking;
    }

    /**
     * @param callable $rawControllerClosure
     * @return callable
     */
    private function decorateWithResponseTypeChecking(callable $rawControllerClosure)
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
