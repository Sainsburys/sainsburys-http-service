<?php
namespace Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder;

use Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sainsburys\HttpService\Components\Middlewares\MiddlewareManager;
use Sainsburys\HttpService\Components\Middlewares\Http\RequestAndResponse;
use Sainsburys\HttpService\Components\Routing\Route;
use Psr\Http\Message\ResponseInterface;

class MiddlewareDecorator implements ControllerClosureBuilder
{
    /** @var ControllerClosureBuilder */
    private $thingBeingDecorated;

    /** @var MiddlewareManager */
    private $middlewareManager;

    /**
     * @param ControllerClosureBuilder $thingBeingDecorated
     * @param MiddlewareManager        $middlewareManager
     */
    public function __construct(ControllerClosureBuilder $thingBeingDecorated, MiddlewareManager $middlewareManager)
    {
        $this->thingBeingDecorated = $thingBeingDecorated;
        $this->middlewareManager = $middlewareManager;
    }

    /**
     * @param ContainerInterface $container
     * @param Route              $route
     * @return callable
     */
    public function buildControllerClosure(ContainerInterface $container, Route $route)
    {
        $rawControllerClosure = $this->thingBeingDecorated->buildControllerClosure($container, $route);
        $closureWhichAppliesMiddlewares = $this->decorateWithMiddlewares($rawControllerClosure, $this->middlewareManager);
        return $closureWhichAppliesMiddlewares;
    }

    /**
     * @param callable          $rawControllerClosure
     * @param MiddlewareManager $middlewareManager
     * @return callable
     */
    private function decorateWithMiddlewares(callable $rawControllerClosure, MiddlewareManager $middlewareManager)
    {
        $controllerClosureWithMiddlewares =
            function (ServerRequestInterface $request, ResponseInterface $response) use (
                $rawControllerClosure, $middlewareManager
            ) {
                $originalRequestAndResponse = new RequestAndResponse($request, $response);
                $postMiddlewareRequestAndResponse = $middlewareManager->applyBeforeMiddlewares($originalRequestAndResponse);

                $controllerResponse = $rawControllerClosure($postMiddlewareRequestAndResponse->request(), $postMiddlewareRequestAndResponse->response());

                $finalResponse = $middlewareManager->applyAfterMiddlewares($controllerResponse);

                return $finalResponse;
            };

        return $controllerClosureWithMiddlewares;
    }
}
