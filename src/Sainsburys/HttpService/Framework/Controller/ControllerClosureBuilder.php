<?php
namespace Sainsburys\HttpService\Framework\Controller;

use Sainsburys\HttpService\Framework\ErrorHandling\ErrorController;
use Interop\Container\ContainerInterface;
use Sainsburys\HttpService\Framework\Routing\Route;

interface ControllerClosureBuilder
{
    /**
     * @param ContainerInterface $container
     * @param Route              $route
     * @param ErrorController    $errorController
     * @return callable
     */
    public function buildControllerClosure(ContainerInterface $container, Route $route, ErrorController $errorController);
}
