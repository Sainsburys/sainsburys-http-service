<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use Sainsburys\HttpService\Dev\MyDiConfig;
use Sainsburys\HttpService\Application;
use Sainsburys\HttpService\Components\DependencyInjection\PimpleContainerInteropAdapter;

$routingConfigFiles = [__DIR__ . '/../config/routing.php'];
$containerWithControllers = PimpleContainerInteropAdapter::constructConfiguredWith(new MyDiConfig());

$application = Application::factory($routingConfigFiles, $containerWithControllers);
$application->run();
