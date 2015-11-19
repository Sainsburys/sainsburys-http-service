<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

////$slim->environment()->offsetSet('PATH_INFO', $_SERVER['SCRIPT_NAME']);
//
//$app = new \Slim\App();
//$app->get('/hello/{name}', function ($request, $response, $args) {
//    $url = $this->router->pathFor('hello', ['name' => 'Josh']);
//
//    return $response;
//})->setName('hello');
//
//$app->run();
//
//exit;





use Pimple\Container;
use Ents\HttpMvcService\Dev\DiServiceProvider;
use Ents\HttpMvcService\Framework\ApplicationBuilder;

$container = new Container();
$serviceProvider = new DiServiceProvider();
$serviceProvider->register($container);

$applicationBuilder = new ApplicationBuilder();
$application = $applicationBuilder->buildApplication([__DIR__ . '/../config/routing.php'], $container);
$application->run();
