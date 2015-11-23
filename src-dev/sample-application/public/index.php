<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use Pimple\Container;
use Ents\HttpMvcService\Dev\MyServiceProvider;
use Ents\HttpMvcService\Framework\ApplicationBuilder;
use Ents\HttpMvcService\Framework\DiContainer\PimpleContainerInteropAdapter;

$containerWithControllers = getConfiguredContainer();

$applicationBuilder = new ApplicationBuilder();
$application = $applicationBuilder->buildApplication([__DIR__ . '/../config/routing.php'], $containerWithControllers);
$application->run();

/**
 * @return PimpleContainerInteropAdapter
 */
function getConfiguredContainer()
{
    $container = new Container();
    $diConfig = new MyServiceProvider();
    $diConfig->register($container);
    $containerAdapter = new PimpleContainerInteropAdapter($container);

    return $containerAdapter;
}
