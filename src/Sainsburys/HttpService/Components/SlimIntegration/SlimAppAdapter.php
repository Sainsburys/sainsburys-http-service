<?php
namespace Sainsburys\HttpService\Components\SlimIntegration;

use Psr\Http\Message\ResponseInterface;
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
            function () use ($slim404Handler) {
                return $slim404Handler;
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

    public function run()
    {
        try {
            if (!$this->hasRoutesConfigured()) {
                throw new CannotRunWithoutRoutes();
            }

            $this->slimApp->run();
        } catch (\Exception $exception) {
            $response = $this->generateErrorResponse($exception);
            $this->dispatchResponse($response);
        }
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
     * @param ResponseInterface $response
     */
    private function dispatchResponse(ResponseInterface $response)
    {
        $this->slimApp->respond($response);
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
