<?php
namespace Sainsburys\HttpService\Test;

use PHPUnit_Framework_TestCase as TestCase;
use Sainsburys\HttpService\Components\Routing\RoutingManager;
use Sainsburys\HttpService\Components\SlimIntegration\SlimAppAdapter;
use Interop\Container\ContainerInterface;
use PHPUnit_Framework_MockObject_MockObject as PhpunitMock;
use Slim\App as SlimApp;
use Slim\Router as SlimRouter;
use Slim\Route as SlimRoute;
use UltraLite\Container\Container;

class RoutingComponentTest extends TestCase
{
    /** @var RoutingManager */
    private $routingManager;

    /** @var SlimAppAdapter */
    private $slimAppAdapter;

    /** @var SlimApp */
    private $slimApp;

    public function setUp()
    {
        $container = new Container();
        $container->configureFromFile(__DIR__ . '/../../../../../config/di.php');
        $this->routingManager = $container->get('sainsburys.sainsburys-http-service.routing-manager');
        $this->slimAppAdapter = $container->get('sainsburys.sainsburys-http-service.slim-app-adapter');
        $this->slimApp        = $container->get('sainsburys.sainsburys-http-service.slim-app');
    }

    public function testApplyingRoutes()
    {
        // ARRANGE
        $container = $this->getMockContainer();
        $routes = [__DIR__ . '/../../../fixtures/sample-routes.php'];

        // ACT
        $this->routingManager->configureSlimAppWithRoutes($routes, $container, $this->slimAppAdapter);

        // ASSERT
        $slimRoute  = $this->getSlimRouteFromApplication($this->slimApp);

        $this->assertEquals(['GET'],        $slimRoute->getMethods());
        $this->assertEquals('/person/{id}', $slimRoute->getPattern());
    }

    /**
     * @return ContainerInterface|PhpunitMock
     */
    private function getMockContainer()
    {
        $container = $this->getMock('\Interop\Container\ContainerInterface', [], [], '', false, false);

        $container
            ->expects($this->once())
            ->method('has')
            ->with('sainsburys.sainsburys-http-service.dev.some-controller')
            ->will($this->returnValue(true));

        return $container;
    }

    /**
     * @param SlimApp $slimApp
     * @return SlimRoute
     */
    private function getSlimRouteFromApplication(SlimApp $slimApp)
    {
        $slimRouter = $slimApp->getContainer()->get('router'); /** @var $slimRouter SlimRouter */
        $slimRoutes = $slimRouter->getRoutes();
        return $slimRoutes['route0'];
    }
}
