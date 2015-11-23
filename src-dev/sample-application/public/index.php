<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use Pimple\Container;
use Ents\HttpMvcService\Dev\MyDiConfig;
use Ents\HttpMvcService\Framework\Application;
use Ents\HttpMvcService\Framework\DiContainer\PimpleContainerInteropAdapter;
use Interop\Container\ContainerInterface;

$containerWithControllers = getConfiguredContainer();

$application = Application::factory([__DIR__ . '/../config/routing.php'], $containerWithControllers);
$application->run();

/**
 * @return ContainerInterface
 */
function getConfiguredContainer()
{
    $pimpleContainer = new Container();
    $pimpleContainer->register(new MyDiConfig());
    $containerAdapter = new PimpleContainerInteropAdapter($pimpleContainer);

    return $containerAdapter;
}
