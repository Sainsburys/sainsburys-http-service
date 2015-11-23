<?php
namespace Ents\HttpMvcService\Framework\Controller\ControllerClosureBuilder;

use Ents\HttpMvcService\Framework\Controller\ControllerClosureBuilder;
use Ents\HttpMvcService\Framework\Exception\InvalidControllerException;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Ents\HttpMvcService\Framework\Routing\Route;
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
     * @return callable
     */
    public function buildControllerClosure(ContainerInterface $container, Route $route)
    {
        $rawControllerClosure = $this->thingBeingDecorated->buildControllerClosure($container, $route);
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
            function (RequestInterface $request, ResponseInterface $response, array $urlVars) use (
                $rawControllerClosure
            ) {

                $response = $rawControllerClosure($request, $response, $urlVars);

                if (!$response instanceof ResponseInterface) {
                    throw new InvalidControllerException();
                }

                return $response;
            };

        return $controllerClosureWithTypeChecking;
    }
}
