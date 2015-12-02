<?php
namespace Sainsburys\HttpService\Test;

use Sainsburys\HttpService\Misc\DiConfig;
use SamBurns\Pimple3ContainerInterop\ServiceContainer;
use PHPUnit_Framework_TestCase as TestCase;

class DiConfigTest extends TestCase
{
    public function testDiConfig()
    {
        // ARRANGE
        $container = ServiceContainer::constructConfiguredWith(new DiConfig());

        // ACT
        $result = $container->get('sainsburys.sainsburys-http-service.application');

        // ASSERT
        $this->assertInstanceOf('\Sainsburys\HttpService\Application', $result);
    }
}
