<?php
namespace Sainsburys\HttpService\Di;

use Sainsburys\HttpService\Framework\Controller\ControllerClosureBuilder\CleanRequestAttributesDecorator;
use Sainsburys\HttpService\Framework\Controller\ControllerClosureBuilder\ErrorHandlingDecorator;
use Sainsburys\HttpService\Framework\Controller\ControllerClosureBuilder\MiddlewareDecorator;
use Sainsburys\HttpService\Framework\Controller\ControllerClosureBuilder\ResponseTypeDecorator;
use Sainsburys\HttpService\Framework\Controller\ControllerClosureBuilder\SimpleControllerClosureBuilder;
use Sainsburys\HttpService\Framework\Application;
use Sainsburys\HttpService\Framework\ErrorHandling\DefaultErrorController;
use Sainsburys\HttpService\Framework\FileWork\PhpArrayConfigFileReader;
use Sainsburys\HttpService\Framework\Middleware\BeforeMiddleware\CleanRequestAttributes;
use Sainsburys\HttpService\Framework\Middleware\BeforeMiddleware\ConvertToJsonResponseObject;
use Sainsburys\HttpService\Framework\Middleware\Manager\MiddlewareManager;
use Sainsburys\HttpService\Framework\Routing\RoutingConfigApplier;
use Sainsburys\HttpService\Framework\Routing\RoutingConfigReader;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Slim\App as SlimApplication;

class FrameworkDiConfig implements ServiceProviderInterface
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
                $defaultErrorController = new DefaultErrorController();
                $middlewareManager      = $container['ents.http-mvc-service.middleware-manager'];

                return new Application(
                    $slimApplication,
                    $routingConfigReader,
                    $routingConfigApplier,
                    $defaultErrorController,
                    $middlewareManager
                );
            };

        $container['ents.http-mvc-service.controller-closure-builder'] =
            function (Container $container) {
                return
                    new ErrorHandlingDecorator(
                        new MiddlewareDecorator(
                            new ResponseTypeDecorator(
                                new CleanRequestAttributesDecorator(
                                    new SimpleControllerClosureBuilder()
                                )
                            ),
                            $container['ents.http-mvc-service.middleware-manager']
                        )
                    );
            };

        $container['ents.http-mvc-service.middleware-manager'] =
            function (Container $container) {
                $middlewareManager = new MiddlewareManager();
                $middlewareManager->addToEndOfBeforeMiddlewareList(new CleanRequestAttributes());
                $middlewareManager->addToEndOfBeforeMiddlewareList(new ConvertToJsonResponseObject());
                return $middlewareManager;
            };
    }
}
