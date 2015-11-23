<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use Pimple\Container;
use Ents\HttpMvcService\Dev\DiServiceProvider;
use Ents\HttpMvcService\Framework\ApplicationBuilder;
use Ents\HttpMvcService\Framework\DiContainer\PimpleContainerInteropAdapter;

$container = new Container();
$serviceProvider = new DiServiceProvider();
$serviceProvider->register($container);
$interopContainer = new PimpleContainerInteropAdapter($container);

$applicationBuilder = new ApplicationBuilder();
$application = $applicationBuilder->buildApplication([__DIR__ . '/../config/routing.php'], $interopContainer);
$application->run();
