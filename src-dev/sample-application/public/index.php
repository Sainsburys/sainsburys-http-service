<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use Sainsburys\HttpService\Dev\MyDiConfig;
use Sainsburys\HttpService\Framework\Application;
use Sainsburys\HttpService\Framework\DiContainer\PimpleContainerInteropAdapter;

$routingConfigFiles = [__DIR__ . '/../config/routing.php'];
$containerWithControllers = PimpleContainerInteropAdapter::constructConfiguredWith(new MyDiConfig());

$application = Application::factory($routingConfigFiles, $containerWithControllers);
$application->run();
