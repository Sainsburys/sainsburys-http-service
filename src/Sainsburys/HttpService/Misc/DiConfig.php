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
use Sainsburys\HttpService\Components\Middlewares\MiddlewareLibrary\BeforeMiddleware\CleanRequestAttributes;
use Sainsburys\HttpService\Components\Middlewares\MiddlewareLibrary\BeforeMiddleware\ConvertToJsonResponseObject;
use Sainsburys\HttpService\Components\Middlewares\MiddlewareManager;
use Sainsburys\HttpService\Components\Routing\RoutingConfigApplier;
use Sainsburys\HttpService\Components\Routing\RoutingConfigReader;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Psr\Log\NullLogger;
use Sainsburys\HttpService\Components\Routing\RoutingManager;
use Sainsburys\HttpService\Components\SlimIntegration\Slim404Handler;
use Sainsburys\HttpService\Components\SlimIntegration\SlimAppAdapter;
use Sainsburys\HttpService\Components\SlimIntegration\SlimErrorHandler;
use SamBurns\ConfigFileParser\ConfigFileParser;
use Slim\App as SlimApplication;

class DiConfig implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['sainsburys.sainsburys-http-service.application'] =
            function (Container $container) {

                $slimAppAdapter         = $container['sainsburys.sainsburys-http-service.slim-app-adapter'];
                $routingConfigManager   = $container['sainsburys.sainsburys-http-service.routing-manager'];
                $middlewareManager      = $container['sainsburys.sainsburys-http-service.middleware-manager'];

                return new Application(
                    $slimAppAdapter,
                    $routingConfigManager,
                    $middlewareManager,
                    new NullLogger(),
                    new DefaultErrorController()
                );
            };

        $container['sainsburys.sainsburys-http-service.slim-app-adapter'] =
            function (Container $container) {
                return new SlimAppAdapter(
                    $container['sainsburys.sainsburys-http-service.slim-app'],
                    new Slim404Handler(),
                    new SlimErrorHandler()
                );
            };

        $container['sainsburys.sainsburys-http-service.slim-app'] =
            function (Container $container) {
                return new SlimApplication();
            };

        $container['sainsburys.sainsburys-http-service.routing-config-reader'] =
            function (Container $container) {
                return new RoutingConfigReader(new ConfigFileParser());
            };

        $container['sainsburys.sainsburys-http-service.routing-config-applier'] =
            function (Container $container) {
                return new RoutingConfigApplier($container['sainsburys.sainsburys-http-service.controller-closure-builder']);
            };

        $container['sainsburys.sainsburys-http-service.routing-manager'] =
            function (Container $container) {
                return new RoutingManager(
                    $container['sainsburys.sainsburys-http-service.routing-config-reader'],
                    $container['sainsburys.sainsburys-http-service.routing-config-applier']
                );
            };

        $container['sainsburys.sainsburys-http-service.controller-closure-builder'] =
            function (Container $container) {
                return
                    new MiddlewareDecorator(
                        new ResponseTypeDecorator(
                            new SimpleControllerClosureBuilder()
                        ),
                        $container['sainsburys.sainsburys-http-service.middleware-manager']
                    );
            };

        $container['sainsburys.sainsburys-http-service.middleware-manager'] =
            function (Container $container) {
                $middlewareManager = new MiddlewareManager();
                $middlewareManager->addToEndOfBeforeMiddlewareList(new CleanRequestAttributes());
                $middlewareManager->addToEndOfBeforeMiddlewareList(new ConvertToJsonResponseObject());
                return $middlewareManager;
            };
    }
}
