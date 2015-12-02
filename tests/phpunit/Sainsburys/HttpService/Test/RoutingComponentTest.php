<?php
namespace Sainsburys\HttpService\Test;

use PHPUnit_Framework_TestCase as TestCase;
use Sainsburys\HttpService\Components\Routing\RoutingConfigReader;
use Sainsburys\HttpService\Components\Routing\RoutingConfigApplier;
use Sainsburys\HttpService\Misc\DiConfig;
use SamBurns\Pimple3ContainerInterop\ServiceContainer;
use Interop\Container\ContainerInterface;
use PHPUnit_Framework_MockObject_MockObject as PhpunitMock;
use Slim\App as SlimApplication;
use Slim\Router as SlimRouter;
use Slim\Route as SlimRoute;

class RoutingComponentTest extends TestCase
{
    /** @var RoutingConfigReader */
    private $routingConfigReader;

    /** @var RoutingConfigApplier */
    private $routingConfigApplier;

    public function setUp()
    {
        $container = ServiceContainer::constructConfiguredWith(new DiConfig());
        $this->routingConfigApplier = $container->get('sainsburys.sainsburys-http-service.routing-config-applier');
        $this->routingConfigReader  = $container->get('sainsburys.sainsburys-http-service.routing-config-reader');
    }

    public function testReadingRoutes()
    {
        // ACT
        $routes = $this->routingConfigReader->getRoutesFromFile(__DIR__ . '/../../../fixtures/sample-routes.php');

        // ASSERT

        // Must be an array with one route
        $this->assertInternalType('array', $routes);
        $this->assertEquals(1, count($routes));
        $this->assertInstanceOf('\Sainsburys\HttpService\Components\Routing\Route', $routes[0]);

        // That route must reflect what is in the config file
        $this->assertEquals('route1', $routes[0]->name());
        $this->assertEquals('sainsburys.sainsburys-http-service.dev.some-controller', $routes[0]->controllerServiceId());
        $this->assertEquals('simpleAction', $routes[0]->actionMethodName());
        $this->assertEquals('GET', $routes[0]->httpVerb());
        $this->assertEquals('/person/{id}', $routes[0]->pathExpression());
    }

    public function testApplyingRoutes()
    {
        // ARRANGE
        $application = new SlimApplication();
        $container = $this->getMockContainer();

        // ACT
        $routes = $this->routingConfigReader->getRoutesFromFile(__DIR__ . '/../../../fixtures/sample-routes.php');
        $this->routingConfigApplier->configureApplicationWithRoutes($application, $routes, $container);

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
