<?php
namespace Ents\HttpMvcService\Framework\Routing;

class RoutingConfigReader
{
    /**
     * @param string $path
     * @return Route[]
     */
    public function getRoutesFromFile($path)
    {
        $fileContentsAsArray = require_once $path;

        $routes = [];

        foreach ($fileContentsAsArray['routes'] as $routeArray) {
            $routes[] = new Route($routeArray);
        }

        return $routes;
    }
}
