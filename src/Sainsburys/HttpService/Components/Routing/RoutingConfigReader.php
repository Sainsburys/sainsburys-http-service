<?php
namespace Sainsburys\HttpService\Components\Routing;

use SamBurns\ConfigFileParser\ConfigFileParser;

class RoutingConfigReader
{
    /** @var ConfigFileParser */
    private $configFileParser;

    public function __construct(ConfigFileParser $configFileParser)
    {
        $this->configFileParser = $configFileParser;
    }

    /**
     * @return Route[]
     */
    public function getRoutesFromFile(string $path): array
    {
        $fileContentsAsArray = $this->configFileParser->parseConfigFile($path);

        $routes = [];

        foreach ($fileContentsAsArray['routes'] as $name => $routeArray) {
            $routes[] = new Route($name, $routeArray);
        }

        return $routes;
    }
}
