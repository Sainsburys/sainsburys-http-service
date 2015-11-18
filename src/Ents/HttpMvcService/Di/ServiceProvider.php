<?php
namespace Ents\HttpMvcService\Di;

use Ents\HttpMvcService\Framework\Application;
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
                $slimApplication     = new SlimApplication();
                $routingConfigReader = new RoutingConfigReader();
                return new Application($slimApplication, $routingConfigReader);
            };
    }
}
