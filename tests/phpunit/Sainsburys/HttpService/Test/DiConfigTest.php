<?php
namespace Sainsburys\HttpService\Test;

use Sainsburys\HttpService\Misc\DiConfig;
use Sainsburys\HttpService\Components\DependencyInjection\PimpleContainerInteropAdapter;
use PHPUnit_Framework_TestCase as TestCase;

class DiConfigTest extends TestCase
{
    public function testDiConfig()
    {
        // ARRANGE
        $container = PimpleContainerInteropAdapter::constructConfiguredWith(new DiConfig());

        // ACT
        $result = $container->get('ents.http-mvc-service.application');

        // ASSERT
        $this->assertInstanceOf('\Sainsburys\HttpService\Application', $result);
    }
}
