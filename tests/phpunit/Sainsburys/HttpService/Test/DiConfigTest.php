<?php
namespace Sainsburys\HttpService\Test;

use Sainsburys\HttpService\Di\FrameworkDiConfig;
use Sainsburys\HttpService\Framework\DiContainer\PimpleContainerInteropAdapter;
use PHPUnit_Framework_TestCase as TestCase;

class DiConfigTest extends TestCase
{
    public function testDiConfig()
    {
        // ARRANGE
        $container = PimpleContainerInteropAdapter::constructConfiguredWith(new FrameworkDiConfig());

        // ACT
        $result = $container->get('ents.http-mvc-service.application');

        // ASSERT
        $this->assertInstanceOf('\Sainsburys\HttpService\Framework\Application', $result);
    }
}
