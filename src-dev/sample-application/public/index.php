<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use Ents\HttpMvcService\Dev\MyDiConfig;
use Ents\HttpMvcService\Framework\Application;
use Ents\HttpMvcService\Framework\DiContainer\PimpleContainerInteropAdapter;

$routingConfigFiles = [__DIR__ . '/../config/routing.php'];
$containerWithControllers = PimpleContainerInteropAdapter::constructConfiguredWith(new MyDiConfig());

$application = Application::factory($routingConfigFiles, $containerWithControllers);
$application->run();
