<?php
namespace Ents\HttpMvcService\Test;

use Ents\HttpMvcService\Di\FrameworkDiConfig;
use Ents\HttpMvcService\Framework\DiContainer\PimpleContainerInteropAdapter;
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
        $this->assertInstanceOf('\Ents\HttpMvcService\Framework\Application', $result);
    }
}
