<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use Pimple\Container;
use Ents\HttpMvcService\Dev\DiServiceProvider;
use Ents\HttpMvcService\Framework\ApplicationBuilder;

$container = new Container();
$serviceProvider = new DiServiceProvider();
$serviceProvider->register($container);

$applicationBuilder = new ApplicationBuilder();
$application = $applicationBuilder->buildApplication([__DIR__ . '/../config/routing.php'], $container);
$application->run();
