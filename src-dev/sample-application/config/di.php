<?php

use Interop\Container\ContainerInterface as Container;

return [

    'sainsburys.sainsburys-http-service.dev.sample-controller' =>
        function (Container $container) {
            return new \Sainsburys\HttpService\Dev\Controller\SimpleController();
        },

    'sainsburys.sainsburys-http-service.dev.controller-with-errors' =>
        function (Container $container) {
            return new \Sainsburys\HttpService\Dev\Controller\ControllerWithErrors();
        },

];
