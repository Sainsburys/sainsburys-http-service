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
        $fileContentsAsArray = require $path;

        $routes = [];

        foreach ($fileContentsAsArray['routes'] as $name => $routeArray) {
            $routes[] = new Route($name, $routeArray);
        }

        return $routes;
    }
}
