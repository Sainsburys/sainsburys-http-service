<?php
namespace Ents\HttpMvcService\Framework\Controller;

use Pimple\Container;
use Ents\HttpMvcService\Framework\Routing\Route;

interface ControllerClosureBuilder
{
    /**
     * @param Container $container
     * @param Route     $route
     * @return callable
     */
    public function buildControllerClosure(Container $container, Route $route);
}
