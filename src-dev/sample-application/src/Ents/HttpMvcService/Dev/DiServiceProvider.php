<?php
namespace Ents\HttpMvcService\Dev;

use Pimple\ServiceProviderInterface;
use Pimple\Container;
use Ents\HttpMvcService\Dev\Controller\SimpleController;
use Ents\HttpMvcService\Dev\Controller\ControllerWithErrors;

class DiServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     */
    public function register(Container $container)
    {
        $container['ents.http-mvc-service.dev.sample-controller'] =
            function (Container $container) {
                return new SimpleController();
            };

        $container['ents.http-mvc-service.dev.controller-with-errors'] =
            function (Container $container) {
                return new ControllerWithErrors();
            };
    }
}
