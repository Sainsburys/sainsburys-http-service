<?php
namespace EntsSpec\Ents\HttpMvcService\Framework\Routing;

use Ents\HttpMvcService\Framework\FileWork\PhpArrayConfigFileReader;
use Ents\HttpMvcService\Framework\Routing\Route;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RoutingConfigReaderSpec extends ObjectBehavior
{
    function let(PhpArrayConfigFileReader $phpArrayConfigFileReader)
    {
        $this->beConstructedWith($phpArrayConfigFileReader);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('\Ents\HttpMvcService\Framework\Routing\RoutingConfigReader');
    }

    function it_can_build_route_objects(PhpArrayConfigFileReader $phpArrayConfigFileReader)
    {
        $phpArrayConfigFileReader->readConfigFile('routing.php')->willReturn(
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
