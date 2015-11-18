<?php
namespace Ents\HttpMvcService\Framework;

use Ents\HttpMvcService\Framework\Routing\RoutingConfigApplier;
use Pimple\Container;
use Slim\Slim as SlimApplication;
use Ents\HttpMvcService\Framework\Routing\RoutingConfigReader;

class Application
{
    /** @var SlimApplication */
    private $slimApplication;

    /** @var RoutingConfigReader */
    private $routingConfigReader;

    /** @var RoutingConfigApplier */
    private $routingConfigApplier;

    /** @var Container */
    private $container;

    /** @var bool */
    private $haveSomeRoutesConfigured = false;

    /**
     * @param SlimApplication      $slimApplication
     * @param RoutingConfigReader  $routingConfigReader
     * @param RoutingConfigApplier $routingConfigApplier
     */
    public function __construct(
        SlimApplication      $slimApplication,
        RoutingConfigReader  $routingConfigReader,
        RoutingConfigApplier $routingConfigApplier
    ) {
        $this->slimApplication       = $slimApplication;
        $this->routingConfigReader   = $routingConfigReader;
        $this->$routingConfigApplier = $routingConfigApplier;
    }

    /**
     * @param Container $container
     */
    public function takeContainerWithControllersConfigured(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $path
     */
    public function takeRoutingConfig($path)
    {
        if (!$this->container) {
            throw new \RuntimeException('Must call takeContainerWithControllersConfigured() before takeRoutingConfig()');
        }

        $routes = $this->routingConfigReader->getRoutesFromFile($path);

        foreach ($routes as $route) {
            $this->routingConfigApplier->configureApplicationWithRoute(
                $this->slimApplication,
                $route,
                $this->container
            );
        }

        $this->haveSomeRoutesConfigured = true;
    }

    /**
     * @throws \RuntimeException
     */
    public function run()
    {
        if (!$this->haveSomeRoutesConfigured) {
            throw new \RuntimeException('Must call takeRoutingConfig() before run()');
        }

        $this->slimApplication->run();
    }
}
