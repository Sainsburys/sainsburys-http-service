<?php
namespace Sainsburys\HttpService\Dev;

use Pimple\ServiceProviderInterface;
use Pimple\Container;
use Sainsburys\HttpService\Dev\Controller\SimpleController;
use Sainsburys\HttpService\Dev\Controller\ControllerWithErrors;

class MyDiConfig implements ServiceProviderInterface
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
