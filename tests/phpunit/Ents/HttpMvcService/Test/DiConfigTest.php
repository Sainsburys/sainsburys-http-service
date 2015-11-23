<?php
namespace Ents\HttpMvcService\Test;

use Ents\HttpMvcService\Di\FrameworkDiConfig;
use PHPUnit_Framework_TestCase as TestCase;
use Pimple\Container;

class DiConfigTest extends TestCase
{
    public function testDiConfig()
    {
        // ARRANGE
        $serviceProvider = new FrameworkDiConfig();
        $container = new Container();
        $serviceProvider->register($container);

        // ACT
        $result = $container['ents.http-mvc-service.application'];

        // ASSERT
        $this->assertInstanceOf('\Ents\HttpMvcService\Framework\Application', $result);
    }
}
