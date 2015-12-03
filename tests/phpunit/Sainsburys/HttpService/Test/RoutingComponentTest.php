<?php
namespace Sainsburys\HttpService\Test;

use PHPUnit_Framework_TestCase as TestCase;
use Sainsburys\HttpService\Components\Routing\RoutingManager;
use Sainsburys\HttpService\Misc\DiConfig;
use SamBurns\Pimple3ContainerInterop\ServiceContainer;
use Interop\Container\ContainerInterface;
use PHPUnit_Framework_MockObject_MockObject as PhpunitMock;
use Slim\App as SlimApplication;
use Slim\Router as SlimRouter;
use Slim\Route as SlimRoute;

class RoutingComponentTest extends TestCase
{
    /** @var RoutingManager */
    private $routingManager;

    public function setUp()
    {
        $container = ServiceContainer::constructConfiguredWith(new DiConfig());
        $this->routingManager = $container->get('sainsburys.sainsburys-http-service.routing-manager');
    }

    public function testApplyingRoutes()
    {
        // ARRANGE
        $application = new SlimApplication();
        $container = $this->getMockContainer();
        $routes = [__DIR__ . '/../../../fixtures/sample-routes.php'];

        // ACT
        $this->routingManager->configureSlimAppWithRoutes($routes, $container, $application);

        // ASSERT
        $slimRoute  = $this->getSlimRouteFromApplication($application);

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
     * @param SlimApplication $slimApplication
     * @return SlimRoute
     */
    private function getSlimRouteFromApplication(SlimApplication $slimApplication)
    {
        $slimRouter = $slimApplication->getContainer()->get('router'); /** @var $slimRouter SlimRouter */
        $slimRoutes = $slimRouter->getRoutes();
        return $slimRoutes['route0'];
    }
}
