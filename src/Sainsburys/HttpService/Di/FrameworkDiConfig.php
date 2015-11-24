<?php
namespace Sainsburys\HttpService\Di;

use Sainsburys\HttpService\Framework\Controller\ControllerClosureBuilder\CleanRequestAttributesDecorator;
use Sainsburys\HttpService\Framework\Controller\ControllerClosureBuilder\ErrorHandlingDecorator;
use Sainsburys\HttpService\Framework\Controller\ControllerClosureBuilder\ResponseTypeDecorator;
use Sainsburys\HttpService\Framework\Controller\ControllerClosureBuilder\SimpleControllerClosureBuilder;
use Sainsburys\HttpService\Framework\Application;
use Sainsburys\HttpService\Framework\ErrorHandling\DefaultErrorController;
use Sainsburys\HttpService\Framework\FileWork\PhpArrayConfigFileReader;
use Sainsburys\HttpService\Framework\Routing\RoutingConfigApplier;
use Sainsburys\HttpService\Framework\Routing\RoutingConfigReader;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Slim\App as SlimApplication;

class FrameworkDiConfig implements ServiceProviderInterface
{
    /**
     * @param Container $pimple
     */
    public function register(Container $pimple)
    {
        $pimple['ents.http-mvc-service.application'] =
            function (Container $container) {

                $slimApplication        = new SlimApplication();
                $routingConfigReader    = new RoutingConfigReader(new PhpArrayConfigFileReader());
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
                return
                    new ErrorHandlingDecorator(
                        new ResponseTypeDecorator(
                            new CleanRequestAttributesDecorator(
                                new SimpleControllerClosureBuilder()
                            )
                        )
                    );
            };
    }
}
