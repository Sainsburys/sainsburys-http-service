<?php
namespace Sainsburys\HttpService\Components\SlimIntegration;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sainsburys\HttpService\Components\ErrorHandling\ErrorController\ErrorControllerManager;
use Sainsburys\HttpService\Components\ErrorHandling\Exceptions\WithStatusCode\CannotRunWithoutRoutes;
use Sainsburys\HttpService\Components\Logging\LoggingManager;
use Sainsburys\HttpService\Components\Routing\Route;
use Slim\App as SlimApp;
use Slim\Router as SlimRouter;

class SlimAppAdapter
{
    /** @var SlimApp */
    private $slimApp;

    /** @var ErrorControllerManager */
    private $errorControllerManager;

    /** @var LoggingManager */
    private $loggingManager;

    /**
     * @param SlimApp                $slimApp
     * @param Slim404Handler         $slim404Handler
     * @param SlimErrorHandler       $slimErrorHandler
     * @param ErrorControllerManager $errorControllerManager
     * @param LoggingManager         $loggingManager
     */
    public function __construct(
        SlimApp                $slimApp,
        Slim404Handler         $slim404Handler,
        SlimErrorHandler       $slimErrorHandler,
        ErrorControllerManager $errorControllerManager,
        LoggingManager         $loggingManager
    ) {
        $this->errorControllerManager = $errorControllerManager;
        $this->loggingManager         = $loggingManager;
        $this->slimApp                = $slimApp;

        $this->slimApp->getContainer()['notFoundHandler'] =
            function () use ($slim404Handler) {
                return $slim404Handler;
            };

        $this->slimApp->getContainer()['errorHandler']    =
            function () use ($slimErrorHandler) {
                return $slimErrorHandler;
            };
    }

    /**
     * @param Route    $route
     * @param \Closure $controllerClosure
     */
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

    /**
     * @param ServerRequestInterface|null $testingRequest
     * @return ResponseInterface
     */
    public function run(ServerRequestInterface $testingRequest = null)
    {
        if ($testingRequest) {

            $this->injectRequestIntoApp($testingRequest, $this->slimApp);
            return $this->getResponseToDispatch();

        } else {

            $response = $this->getResponseToDispatch();
            $this->dispatchResponse($response);
            return $response;

        }
    }

    /**
     * @return ResponseInterface
     */
    private function getResponseToDispatch()
    {
        try {
            if (!$this->hasRoutesConfigured()) {
                throw new CannotRunWithoutRoutes();
            }
            return $this->slimApp->run(true);
        } catch (\Exception $exception) {
            return $this->generateErrorResponse($exception);
        }
    }

    /**
     * @param ServerRequestInterface $request
     * @param SlimApp                $slimApp
     */
    private function injectRequestIntoApp(ServerRequestInterface $request, SlimApp $slimApp)
    {
        $slimApp->getContainer()['request'] =
            function () use ($request) {
                return $request;
            };
    }

    /**
     * @param ResponseInterface $response
     */
    private function dispatchResponse(ResponseInterface $response)
    {
        $this->slimApp->respond($response);
    }

    /**
     * @param \Exception $exception
     * @return ResponseInterface
     */
    private function generateErrorResponse(\Exception $exception)
    {
        $errorController = $this->errorControllerManager->errorController();
        $logger = $this->loggingManager->logger();

        return $errorController->handleError($exception, $logger);
    }

    /**
     * @return bool
     */
    private function hasRoutesConfigured()
    {
        $slimContainer = $this->slimApp->getContainer();
        $slimRouter = $slimContainer->get('router'); /** @var $slimRouter SlimRouter */
        return (bool)count($slimRouter->getRoutes());
    }
}
