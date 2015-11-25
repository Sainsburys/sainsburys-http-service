<?php
namespace Sainsburys\HttpService\Components\ControllerClosures;

use Interop\Container\ContainerInterface;
use Sainsburys\HttpService\Components\Routing\Route;

interface ControllerClosureBuilder
{
    /**
     * @param ContainerInterface $container
     * @param Route              $route
     * @return callable
     */
    public function buildControllerClosure(ContainerInterface $container, Route $route);
}
