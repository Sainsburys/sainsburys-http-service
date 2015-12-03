<?php
namespace Sainsburys\HttpService\Components\Routing;

use SamBurns\ConfigFileParser\FileParsing\ParsableFileFactory;

class RoutingConfigReader
{
    /** @var ParsableFileFactory */
    private $parsableFileFactory;

    /**
     * @param ParsableFileFactory $parsableFileFactory
     */
    public function __construct(ParsableFileFactory $parsableFileFactory)
    {
        $this->parsableFileFactory = $parsableFileFactory;
    }

    /**
     * @param string $path
     * @return Route[]
     */
    public function getRoutesFromFile($path)
    {
        $parsableFile = $this->parsableFileFactory->getParsableFileFromPath($path);
        $fileContentsAsArray = $parsableFile->toArray();

        $routes = [];

        foreach ($fileContentsAsArray['routes'] as $name => $routeArray) {
            $routes[] = new Route($name, $routeArray);
        }

        return $routes;
    }
}
