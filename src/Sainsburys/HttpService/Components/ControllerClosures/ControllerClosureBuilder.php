<?php
namespace Sainsburys\HttpService\Components\ControllerClosures;

use Interop\Container\ContainerInterface;
use Sainsburys\HttpService\Components\Routing\Route;

interface ControllerClosureBuilder
{
    public function buildControllerClosure(ContainerInterface $container, Route $route): \Closure;
}
