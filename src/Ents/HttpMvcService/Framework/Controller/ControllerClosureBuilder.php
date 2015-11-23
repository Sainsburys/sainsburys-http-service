<?php
namespace Ents\HttpMvcService\Framework\Controller;

use Interop\Container\ContainerInterface;
use Ents\HttpMvcService\Framework\Routing\Route;

interface ControllerClosureBuilder
{
    /**
     * @param ContainerInterface $container
     * @param Route              $route
     * @return callable
     */
    public function buildControllerClosure(ContainerInterface $container, Route $route);
}
