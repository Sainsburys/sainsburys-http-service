<?php
namespace Ents\HttpMvcService\Di;

use Ents\HttpMvcService\Framework\Controller\ControllerClosureBuilder\ErrorHandlingDecorator;
use Ents\HttpMvcService\Framework\Controller\ControllerClosureBuilder\ResponseTypeDecorator;
use Ents\HttpMvcService\Framework\Controller\ControllerClosureBuilder\SimpleControllerClosureBuilder;
use Ents\HttpMvcService\Framework\Controller\ControllerClosureBuilderFactory;
use Ents\HttpMvcService\Framework\Application;
use Ents\HttpMvcService\Framework\ErrorHandling\DefaultErrorController;
use Ents\HttpMvcService\Framework\Routing\RoutingConfigApplier;
use Ents\HttpMvcService\Framework\Routing\RoutingConfigReader;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Slim\App as SlimApplication;

class ServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $pimple
     */
    public function register(Container $pimple)
    {
        $pimple['ents.http-mvc-service.application'] =
            function (Container $container) {

                $slimApplication        = new SlimApplication();
                $routingConfigReader    = new RoutingConfigReader();
                $routingConfigApplier   = new RoutingConfigApplier($container['ents.http-mvc-service.controller-closure-builder']);
                $defaultErrorController = new DefaultErrorController();

                return new Application(
                    $slimApplication,
                    $routingConfigReader,
                    $routingConfigApplier,
                    $defaultErrorController
                );
            };

        $pimple['ents.http-mvc-service.controller-closure-builder'] =
            function (Container $container) {

                return new ErrorHandlingDecorator(
                    new ResponseTypeDecorator(
                        new SimpleControllerClosureBuilder()
                    )
                );
            };
    }
}
