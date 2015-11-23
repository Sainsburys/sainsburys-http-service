<?php
namespace Ents\HttpMvcService\Framework\Controller;

use Ents\HttpMvcService\Framework\ErrorHandling\ErrorController;
use Interop\Container\ContainerInterface;
use Ents\HttpMvcService\Framework\Routing\Route;

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
