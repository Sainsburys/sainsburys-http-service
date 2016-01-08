<?php
namespace Sainsburys\HttpService\Test;

use Sainsburys\HttpService\Application;
use PHPUnit_Framework_TestCase as TestCase;
use UltraLite\Container\Container;

class DiConfigTest extends TestCase
{
    public function testDiConfig()
    {
        // ARRANGE
        $container = new Container();
        $container->configureFromFile(__DIR__ . '/../../../../../config/di.php');

        // ACT
        $result = $container->get('sainsburys.sainsburys-http-service.application');

        // ASSERT
        $this->assertInstanceOf(Application::class, $result);
    }
}
