<?php
namespace Sainsburys\HttpService\Misc;

use Sainsburys\HttpService\Components\ErrorHandling\ErrorController\ErrorControllerManager;
use Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder\ErrorHandlingDecorator;
use Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder\MiddlewareDecorator;
use Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder\ResponseTypeDecorator;
use Sainsburys\HttpService\Components\ControllerClosures\ControllerClosureBuilder\SimpleControllerClosureBuilder;
use Sainsburys\HttpService\Application;
use Sainsburys\HttpService\Components\ErrorHandling\ErrorController\DefaultErrorController;
use Sainsburys\HttpService\Components\Routing\FileWork\PhpArrayConfigFileReader;
use Sainsburys\HttpService\Components\Middlewares\MiddlewareLibrary\BeforeMiddleware\CleanRequestAttributes;
use Sainsburys\HttpService\Components\Middlewares\MiddlewareLibrary\BeforeMiddleware\ConvertToJsonResponseObject;
use Sainsburys\HttpService\Components\Middlewares\MiddlewareManager;
use Sainsburys\HttpService\Components\Routing\RoutingConfigApplier;
use Sainsburys\HttpService\Components\Routing\RoutingConfigReader;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Slim\App as SlimApplication;

class DiConfig implements ServiceProviderInterface
{
    /**
     * @param Container $container
     */
    public function register(Container $container)
    {
        $container['ents.http-mvc-service.application'] =
            function (Container $container) {

                $slimApplication        = new SlimApplication();
                $routingConfigReader    = new RoutingConfigReader(new PhpArrayConfigFileReader());
                $routingConfigApplier   = new RoutingConfigApplier($container['ents.http-mvc-service.controller-closure-builder']);
                $errorControllerManager = $container['ents.http-mvc-service.error-controller-manager'];
                $middlewareManager      = $container['ents.http-mvc-service.middleware-manager'];

                return new Application(
                    $slimApplication,
                    $routingConfigReader,
                    $routingConfigApplier,
                    $errorControllerManager,
                    $middlewareManager
                );
            };

        $container['ents.http-mvc-service.controller-closure-builder'] =
            function (Container $container) {
                return
                    new ErrorHandlingDecorator(
                        new MiddlewareDecorator(
                            new ResponseTypeDecorator(
                                new SimpleControllerClosureBuilder()
                            ),
                            $container['ents.http-mvc-service.middleware-manager']
                        ),
                        $container['ents.http-mvc-service.error-controller-manager']
                    );
            };

        $container['ents.http-mvc-service.middleware-manager'] =
            function (Container $container) {
                $middlewareManager = new MiddlewareManager();
                $middlewareManager->addToEndOfBeforeMiddlewareList(new CleanRequestAttributes());
                $middlewareManager->addToEndOfBeforeMiddlewareList(new ConvertToJsonResponseObject());
                return $middlewareManager;
            };

        $container['ents.http-mvc-service.error-controller-manager'] =
            function (Container $container) {
                return new ErrorControllerManager(new DefaultErrorController());
            };
    }
}
