<?php
namespace Sainsburys\HttpService\Misc;

use Sainsburys\HttpService\Components\ErrorHandling\ErrorController\ErrorControllerManager;
use Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder\ErrorHandlingDecorator;
use Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder\MiddlewareDecorator;
use Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder\ResponseTypeDecorator;
use Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder\SimpleControllerClosureBuilder;
use Sainsburys\HttpService\Application;
use Sainsburys\HttpService\Components\ErrorHandling\ErrorController\DefaultErrorController;
use Sainsburys\HttpService\Components\Logging\LoggingManager;
use Sainsburys\HttpService\Components\Routing\FileWork\PhpArrayConfigFileReader;
use Sainsburys\HttpService\Components\Middlewares\MiddlewareLibrary\BeforeMiddleware\CleanRequestAttributes;
use Sainsburys\HttpService\Components\Middlewares\MiddlewareLibrary\BeforeMiddleware\ConvertToJsonResponseObject;
use Sainsburys\HttpService\Components\Middlewares\MiddlewareManager;
use Sainsburys\HttpService\Components\Routing\RoutingConfigApplier;
use Sainsburys\HttpService\Components\Routing\RoutingConfigReader;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Psr\Log\NullLogger;
use Slim\App as SlimApplication;

class DiConfig implements ServiceProviderInterface
{
    /**
     * @param Container $container
     */
    public function register(Container $container)
    {
        $container['sainsburys.sainsburys-http-service.application'] =
            function (Container $container) {

                $slimApplication        = new SlimApplication();
                $routingConfigReader    = $container['sainsburys.sainsburys-http-service.routing-config-reader'];
                $routingConfigApplier   = $container['sainsburys.sainsburys-http-service.routing-config-applier'];
                $errorControllerManager = $container['sainsburys.sainsburys-http-service.error-controller-manager'];
                $middlewareManager      = $container['sainsburys.sainsburys-http-service.middleware-manager'];
                $loggingManager         = $container['sainsburys.sainsburys-http-service.logging-manager'];

                return new Application(
                    $slimApplication,
                    $routingConfigReader,
                    $routingConfigApplier,
                    $errorControllerManager,
                    $middlewareManager,
                    $loggingManager
                );
            };

        $container['sainsburys.sainsburys-http-service.routing-config-reader'] =
            function (Container $container) {
                return new RoutingConfigReader(new PhpArrayConfigFileReader());
            };

        $container['sainsburys.sainsburys-http-service.routing-config-applier'] =
            function (Container $container) {
                return new RoutingConfigApplier($container['sainsburys.sainsburys-http-service.controller-closure-builder']);
            };

        $container['sainsburys.sainsburys-http-service.controller-closure-builder'] =
            function (Container $container) {
                return
                    new ErrorHandlingDecorator(
                        new MiddlewareDecorator(
                            new ResponseTypeDecorator(
                                new SimpleControllerClosureBuilder()
                            ),
                            $container['sainsburys.sainsburys-http-service.middleware-manager']
                        ),
                        $container['sainsburys.sainsburys-http-service.error-controller-manager'],
                        $container['sainsburys.sainsburys-http-service.logging-manager']
                    );
            };

        $container['sainsburys.sainsburys-http-service.middleware-manager'] =
            function (Container $container) {
                $middlewareManager = new MiddlewareManager();
                $middlewareManager->addToEndOfBeforeMiddlewareList(new CleanRequestAttributes());
                $middlewareManager->addToEndOfBeforeMiddlewareList(new ConvertToJsonResponseObject());
                return $middlewareManager;
            };

        $container['sainsburys.sainsburys-http-service.error-controller-manager'] =
            function (Container $container) {
                return new ErrorControllerManager(new DefaultErrorController());
            };

        $container['sainsburys.sainsburys-http-service.logging-manager'] =
            function (Container $container) {
                return new LoggingManager(new NullLogger());
            };
    }
}
