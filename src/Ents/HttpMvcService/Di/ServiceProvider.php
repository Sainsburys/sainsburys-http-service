<?php
namespace Ents\HttpMvcService\Di;

use Ents\HttpMvcService\Framework\Application;
use Ents\HttpMvcService\Framework\Routing\RoutingConfigApplier;
use Ents\HttpMvcService\Framework\Routing\RoutingConfigReader;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Slim\Slim as SlimApplication;

class ServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $pimple
     */
    public function register(Container $pimple)
    {
        $pimple['ents.http-mvc-service.application'] =
            function (Container $container) {
                $slimApplication      = $container['ents.http-mvc-service.slim-application'];
                $routingConfigReader  = new RoutingConfigReader();
                $routingConfigApplier = new RoutingConfigApplier();
                return new Application($slimApplication, $routingConfigReader, $routingConfigApplier);
            };

        $pimple['ents.http-mvc-service.slim-application'] =
            function (Container $container) {
                $slimApplication = new SlimApplication();
                // @todo Disgusting hack for compatibility with PHP webserver - remove
                $slimApplication->environment()->offsetSet('PATH_INFO', $_SERVER['SCRIPT_NAME']);
                return $slimApplication;
            };
    }
}
