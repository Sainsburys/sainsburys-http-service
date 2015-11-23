<?php
namespace Ents\HttpMvcService\Framework;

use Ents\HttpMvcService\Di\ServiceProvider;
use Interop\Container\ContainerInterface;
use Pimple\Container as PimpleContainer;

class ApplicationBuilder
{
    /**
     * @param string[]           $routingConfigFilePaths
     * @param ContainerInterface $containerWithControllers
     * @return Application
     */
    public function buildApplication($routingConfigFilePaths, ContainerInterface $containerWithControllers)
    {
        $application = $this->getApplicationObject();

        $application->takeContainerWithControllersConfigured($containerWithControllers);

        foreach ($routingConfigFilePaths as $path) {
            $application->takeRoutingConfig($path);
        }

        return $application;
    }

    /**
     * @return Application
     */
    private function getApplicationObject()
    {
        $containerWithFramework = new PimpleContainer();
        $diServiceProvider = new ServiceProvider();
        $diServiceProvider->register($containerWithFramework);

        return $containerWithFramework['ents.http-mvc-service.application'];
    }
}
