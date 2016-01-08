<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use Sainsburys\HttpService\Application;
use UltraLite\Container\Container;

$routingConfigFiles = [__DIR__ . '/../config/routing.php'];
$diConfigFile       = __DIR__ . '/../config/di.php';

$containerWithControllers = new Container();
$containerWithControllers->configureFromFile($diConfigFile);

$application = Application::factory($routingConfigFiles, $containerWithControllers);
$application->run();
