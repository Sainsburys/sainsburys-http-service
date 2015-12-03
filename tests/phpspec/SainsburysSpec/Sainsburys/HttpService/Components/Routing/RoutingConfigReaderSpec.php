<?php
namespace SainsburysSpec\Sainsburys\HttpService\Components\Routing;

use Sainsburys\HttpService\Components\Routing\RoutingConfigReader;
use SamBurns\ConfigFileParser\FileParsing\ParsableFileFactory;
use SamBurns\ConfigFileParser\FileParsing\ParsableFile;
use Sainsburys\HttpService\Components\Routing\Route;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin RoutingConfigReader
 */
class RoutingConfigReaderSpec extends ObjectBehavior
{
    function let(ParsableFileFactory $parsableFileFactory)
    {
        $this->beConstructedWith($parsableFileFactory);
    }

    function it_is_initialisable()
    {
        $this->shouldHaveType('\Sainsburys\HttpService\Components\Routing\RoutingConfigReader');
    }

    function it_can_build_route_objects(ParsableFileFactory $parsableFileFactory, ParsableFile $parsableFile)
    {
        $parsableFileFactory->getParsableFileFromPath('routing.php')->willReturn($parsableFile);
        $parsableFile->toArray()->willReturn(
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
