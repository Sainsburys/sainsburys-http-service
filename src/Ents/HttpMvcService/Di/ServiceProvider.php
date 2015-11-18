<?php
namespace Ents\HttpMvcService\Di;

use Ents\HttpMvcService\Framework\FrontController;
use Ents\HttpMvcService\Framework\Router;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['ents.http-mvc-service.front-controller'] =
            function (Container $container) {
                return new FrontController();
            };

        $pimple['ents.http-mvc-service.router'] =
            function (Container $container) {
                return new Router();
            };
    }
}
