<?php
namespace Ents\HttpMvcService\Framework;

use Ents\HttpMvcService\Di\ServiceProvider;
use Pimple\Container;

class ApplicationBuilder
{
    /**
     * @param string[]  $routingConfigFilePaths
     * @param Container $containerWithControllers
     * @return Application
     */
    public function buildApplication($routingConfigFilePaths, Container $containerWithControllers)
    {
        $diServiceProvider = new ServiceProvider();
        $diServiceProvider->register($containerWithControllers);

        $application = $containerWithControllers['ents.http-mvc-service.application']; /** @var $application Application */
        $application->takeContainerWithControllersConfigured($containerWithControllers);

        foreach ($routingConfigFilePaths as $path) {
            $application->takeRoutingConfig($path);
        }

        return $application;
    }
}
