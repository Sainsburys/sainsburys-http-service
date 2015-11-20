<?php
namespace Ents\HttpMvcService\Di;

use Ents\HttpMvcService\Framework\Application;
use Ents\HttpMvcService\Framework\ControllerGenerator;
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
                $slimApplication      = new SlimApplication();
                $routingConfigReader  = new RoutingConfigReader();
                $routingConfigApplier = new RoutingConfigApplier(new ControllerGenerator());
                return new Application($slimApplication, $routingConfigReader, $routingConfigApplier);
            };
    }
}
