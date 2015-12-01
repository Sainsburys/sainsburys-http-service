<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use Sainsburys\HttpService\Dev\MyDiConfig;
use Sainsburys\HttpService\Application;
use SamBurns\Pimple3ContainerInterop\ServiceContainer;

$routingConfigFiles = [__DIR__ . '/../config/routing.php'];
$containerWithControllers = ServiceContainer::constructConfiguredWith(new MyDiConfig());

$application = Application::factory($routingConfigFiles, $containerWithControllers);
$application->run();
