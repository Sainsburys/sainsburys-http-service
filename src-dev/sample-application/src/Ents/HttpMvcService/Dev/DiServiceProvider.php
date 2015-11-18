<?php
namespace Ents\HttpMvcService\Dev;

use Pimple\ServiceProviderInterface;
use Pimple\Container;

class DiServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     */
    public function register(Container $container)
    {
        $container['ents.http-mvc-service.dev.simple-controller'] =
            function (Container $container) {
                return new SimpleController();
            };
    }
}
