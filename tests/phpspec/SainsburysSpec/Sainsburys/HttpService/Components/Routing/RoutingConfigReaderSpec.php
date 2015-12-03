<?php
namespace SainsburysSpec\Sainsburys\HttpService\Components\Routing;

use Sainsburys\HttpService\Components\Routing\RoutingConfigReader;
use SamBurns\ConfigFileParser\ConfigFileParser;
use Sainsburys\HttpService\Components\Routing\Route;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin RoutingConfigReader
 */
class RoutingConfigReaderSpec extends ObjectBehavior
{
    function let(ConfigFileParser $configFileParser)
    {
        $this->beConstructedWith($configFileParser);
    }

    function it_is_initialisable()
    {
        $this->shouldHaveType('\Sainsburys\HttpService\Components\Routing\RoutingConfigReader');
    }

    function it_can_build_route_objects(ConfigFileParser $configFileParser)
    {
        $configFileParser->parseConfigFile('routing.php')->willReturn(
            [
                'routes' => [
                    'route-name' => [
                        'http-verb'             => 'GET',
                        'path'                  => '/person/:id',
                        'controller-service-id' => 'example-controller-service-id',
                        'action-method-name'    => 'exampleAction'
                    ]
                ]
            ]
        );

        $expectedRoute = new Route(
            'route-name',
            [
                'http-verb'             => 'GET',
                'path'                  => '/person/:id',
                'controller-service-id' => 'example-controller-service-id',
                'action-method-name'    => 'exampleAction'
            ]
        );

        $this->getRoutesFromFile('routing.php')->shouldBeLike([$expectedRoute]);
    }
}
