<?php
namespace Ents\HttpMvcService\Framework\Controller\ControllerClosureBuilder;

use Ents\HttpMvcService\Framework\Controller\ControllerClosureBuilder;
use Pimple\Container;
use Psr\Http\Message\RequestInterface;
use Ents\HttpMvcService\Framework\Routing\Route;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;

class ArrayToResponseConversionDecorator implements ControllerClosureBuilder
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
     * @param Container $container
     * @param Route     $route
     * @return callable
     */
    public function buildControllerClosure(Container $container, Route $route)
    {
        $rawControllerClosure = $this->thingBeingDecorated->buildControllerClosure($container, $route);
        $closureWhichAlsoArrayToResponseConversion = $this->decorateWithArrayToResponseConversion(
            $rawControllerClosure
        );
        return $closureWhichAlsoArrayToResponseConversion;
    }

    /**
     * @param callable $rawControllerClosure
     * @return callable
     */
    private function decorateWithArrayToResponseConversion(callable $rawControllerClosure)
    {
        $controllerClosureWithTypeChecking =
            function (RequestInterface $request, ResponseInterface $response, array $urlVars) use (
                $rawControllerClosure
            ) {

                $response = $rawControllerClosure($request, $response, $urlVars);

                if (is_array($response)) {
                    $response = new JsonResponse($response);
                }

                return $response;
            };

        return $controllerClosureWithTypeChecking;
    }
}
