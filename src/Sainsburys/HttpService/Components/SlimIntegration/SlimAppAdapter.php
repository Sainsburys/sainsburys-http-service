<?php
namespace Sainsburys\HttpService\Components\SlimIntegration;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Sainsburys\HttpService\Components\ErrorHandling\ErrorController\ErrorController;
use Sainsburys\HttpService\Components\ErrorHandling\Exceptions\WithStatusCode\CannotRunWithoutRoutes;
use Sainsburys\HttpService\Components\Routing\Route;
use Slim\App as SlimApp;
use Slim\Router as SlimRouter;

class SlimAppAdapter
{
    /** @var SlimApp */
    private $slimApp;

    public function __construct(SlimApp $slimApp, Slim404Handler $slim404Handler, SlimErrorHandler $slimErrorHandler)
    {
        $this->slimApp = $slimApp;

        $this->slimApp->getContainer()['notFoundHandler'] =
            function () use ($slim404Handler) {
                return $slim404Handler;
            };

        $this->slimApp->getContainer()['errorHandler'] =
            function () use ($slimErrorHandler) {
                return $slimErrorHandler;
            };
    }

    public function addRoute(Route $route, \Closure $controllerClosure)
    {
        $this
            ->slimApp
            ->map(
                [$route->httpVerb()],
                $route->pathExpression(),
                $controllerClosure
            )
            ->setName($route->name())
        ;
    }

    public function run(
        ServerRequestInterface $testingRequest = null,
        LoggerInterface        $logger,
        ErrorController        $errorController
    ): ResponseInterface {
        if ($testingRequest) {
            $this->injectRequestIntoApp($testingRequest, $this->slimApp);
            return $this->getResponseToDispatch($errorController, $logger);
        } else {
            $response = $this->getResponseToDispatch($errorController, $logger);
            $this->dispatchResponse($response);
            return $response;
        }
    }

    private function getResponseToDispatch(ErrorController $errorController, LoggerInterface $logger): ResponseInterface
    {
        try {
            if (!$this->hasRoutesConfigured()) {
                throw new CannotRunWithoutRoutes();
            }
            return $this->slimApp->run(true);
        } catch (\Exception $exception) {
            return $this->generateErrorResponse($errorController, $exception, $logger);
        }
    }

    private function injectRequestIntoApp(ServerRequestInterface $request, SlimApp $slimApp)
    {
        $slimApp->getContainer()['request'] =
            function () use ($request) {
                return $request;
            };
    }

    private function dispatchResponse(ResponseInterface $response)
    {
        $this->slimApp->respond($response);
    }

    private function generateErrorResponse(
        ErrorController $errorController,
        \Exception      $exception,
        LoggerInterface $logger
    ): ResponseInterface {
        return $errorController->handleError($exception, $logger);
    }

    private function hasRoutesConfigured(): bool
    {
        $slimContainer = $this->slimApp->getContainer();
        $slimRouter = $slimContainer->get('router'); /** @var $slimRouter SlimRouter */
        return (bool)count($slimRouter->getRoutes());
    }
}
