<?php
namespace Ents\HttpMvcService\Framework\Controller\ControllerClosureBuilder;

use Ents\HttpMvcService\Framework\Controller\ControllerClosureBuilder;
use Ents\HttpMvcService\Framework\ErrorHandling\ErrorController;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Ents\HttpMvcService\Framework\Routing\Route;
use Psr\Http\Message\ResponseInterface;

class ErrorHandlingDecorator implements ControllerClosureBuilder
{
    /** @var ControllerClosureBuilder */
    private $thingBeingDecorated;

    /** @var ErrorController */
    private $errorController;

    /**
     * @param ControllerClosureBuilder $thingBeingDecorated
     * @param ErrorController          $errorController
     */
    public function __construct(ControllerClosureBuilder $thingBeingDecorated, ErrorController $errorController)
    {
        $this->thingBeingDecorated = $thingBeingDecorated;
        $this->errorController     = $errorController;

    }

    /**
     * @param ContainerInterface $container
     * @param Route              $route
     * @return callable
     */
    public function buildControllerClosure(ContainerInterface $container, Route $route)
    {
        $rawControllerClosure = $this->thingBeingDecorated->buildControllerClosure($container, $route);
        $closureWhichAlsoDoesErrorHandling = $this->decorateWithErrorHandling(
            $rawControllerClosure, $this->errorController
        );
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
            function (RequestInterface $request, ResponseInterface $response, array $urlVars) use (
                $rawControllerClosure, $errorController
            ) {
                try {
                    return $rawControllerClosure($request, $response, $urlVars);
                } catch (\Exception $exception) {
                    return $errorController->handleError($exception);
                }
            };

        return $controllerClosureWithErrorHandling;
    }
}
