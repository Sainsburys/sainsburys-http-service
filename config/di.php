<?php

use Interop\Container\ContainerInterface as Container;

return [

    'sainsburys.sainsburys-http-service.application' =>
        function (Container $container) {
            $slimAppAdapter         = $container->get('sainsburys.sainsburys-http-service.slim-app-adapter');
            $routingConfigManager   = $container->get('sainsburys.sainsburys-http-service.routing-manager');
            $middlewareManager      = $container->get('sainsburys.sainsburys-http-service.middleware-manager');

            return new \Sainsburys\HttpService\Application(
                $slimAppAdapter,
                $routingConfigManager,
                $middlewareManager,
                new \Psr\Log\NullLogger(),
                new \Sainsburys\HttpService\Components\ErrorHandling\ErrorController\DefaultErrorController()
            );
        },

    'sainsburys.sainsburys-http-service.slim-app-adapter' =>
        function (Container $container) {
            return new \Sainsburys\HttpService\Components\SlimIntegration\SlimAppAdapter(
                $container->get('sainsburys.sainsburys-http-service.slim-app'),
                new \Sainsburys\HttpService\Components\SlimIntegration\Slim404Handler(),
                new \Sainsburys\HttpService\Components\SlimIntegration\SlimErrorHandler()
            );
        },

    'sainsburys.sainsburys-http-service.slim-app' =>
        function (Container $container) {
            return new \Slim\App();
        },

    'sainsburys.sainsburys-http-service.routing-config-reader' =>
        function (Container $container) {
            return new \Sainsburys\HttpService\Components\Routing\RoutingConfigReader(new \SamBurns\ConfigFileParser\ConfigFileParser());
        },

    'sainsburys.sainsburys-http-service.routing-config-applier' =>
        function (Container $container) {
            $controllerClosureBuilder = $container->get('sainsburys.sainsburys-http-service.controller-closure-builder');
            return new \Sainsburys\HttpService\Components\Routing\RoutingConfigApplier($controllerClosureBuilder);
        },

    'sainsburys.sainsburys-http-service.routing-manager' =>
        function (Container $container) {
            $routingConfigReader = $container->get('sainsburys.sainsburys-http-service.routing-config-reader');
            $routingConfigApplier = $container->get('sainsburys.sainsburys-http-service.routing-config-applier');
            return new \Sainsburys\HttpService\Components\Routing\RoutingManager($routingConfigReader, $routingConfigApplier);
        },

    'sainsburys.sainsburys-http-service.controller-closure-builder' =>
        function (Container $container) {
            return
                new \Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder\MiddlewareDecorator(
                    new \Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder\ResponseTypeDecorator(
                        new \Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder\SimpleControllerClosureBuilder()
                    ),
                    $container->get('sainsburys.sainsburys-http-service.middleware-manager')
                );
        },

    'sainsburys.sainsburys-http-service.middleware-manager' =>
        function (Container $container) {
            $cleanRequestAttributesMiddleware = new \Sainsburys\HttpService\Components\Middlewares\MiddlewareLibrary\BeforeMiddleware\CleanRequestAttributes();
            $convertToJsonResponseObjectMiddleware = new \Sainsburys\HttpService\Components\Middlewares\MiddlewareLibrary\BeforeMiddleware\ConvertToJsonResponseObject();
            $middlewareManager = new \Sainsburys\HttpService\Components\Middlewares\MiddlewareManager();
            $middlewareManager->addToEndOfBeforeMiddlewareList($cleanRequestAttributesMiddleware);
            $middlewareManager->addToEndOfBeforeMiddlewareList($convertToJsonResponseObjectMiddleware);

            return $middlewareManager;
        },

];
