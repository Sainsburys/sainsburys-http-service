[![Build Status](https://travis-ci.org/anobii/sainsburys-http-service.svg?branch=travis)](https://travis-ci.org/anobii/sainsburys-http-service)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/anobii/sainsburys-http-service/badges/quality-score.png?b=master&s=ad6f751b0f40e1246e30ac7b185b4a3b35c5fcc3)](https://scrutinizer-ci.com/g/anobii/sainsburys-http-service/?branch=master)
[![Coverage Status](https://coveralls.io/repos/anobii/sainsburys-http-service/badge.svg?branch=master&service=github)](https://coveralls.io/github/anobii/sainsburys-http-service?branch=master)

![logo](http://www.sainsburys.co.uk/homepage/images/sainsburys.png)

Sainsburys HTTP Service Framework
=================================

PHP micro-framework for small REST or HTTP RPC services.  Built and open sourced by J Sainsbury plc.

The framework is basically a wrapper for the Slim micro-framework in PHP, but works only with a more structured
application.

It currently relies on Slim 3.0.0-RC3.

Basic Usage
-----------

See the [sample application](https://github.com/anobii/sainsburys-http-service/tree/master/src-dev/sample-application)
for an example of how to use it.  The sample application is used by the automated tests as well, so will be up-to-date.

Core Concepts
-------------

**Standards Compliance**

The framework aims to comply with the [PSR-3](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md)
standard for interchangable loggers, the [PSR-7](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-7-http-message.md)
standard for interchangable HTTP request and response objects, the [Container-Interop](https://github.com/container-interop/container-interop)
standard for interchangable Dependency Injection Containers, and with [semantic versioning](http://semver.org/) in its
release numbers.

**Controllers and Dependency Injection**

This is what a controller should look like:

```php
<?php
namespace MyNamespace;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class EmptyController
{
    public function emptyAction(ServerRequestInterface $request, ResponseInterface $response)
    {
        return $response;
    }
}
```

Controllers must be objects, not closures.  No abstract controller is provided - controllers should be stand-alone
objects with no inheritance.  Controllers will be retrieved from a dependency injection container.

Your routing config will map a route pattern to the service ID of the controller.  Provide a Container Interop
container with your controllers in it.  A [standards-compliant Pimple 3
wrapper](https://github.com/Sam-Burns/pimple3-containerinterop) is used in the [example index
file](https://github.com/anobii/sainsburys-http-service/blob/master/src-dev/sample-application/public/index.php) provided.

Try looking at the [example routing file](https://github.com/anobii/sainsburys-http-service/blob/master/src-dev/sample-application/config/routing.php)
and [typical dependency injection configuration](https://github.com/anobii/sainsburys-http-service/blob/master/src-dev/sample-application/src/Sainsburys/HttpService/Dev/MyDiConfig.php)
for a clear example of this.  Your routing config file may be a .php 'return array' file, a JSON file, a YAML file or a
.ini file.

**Controller Actions - acceptable return types**

Controller actions must return a PSR-7 HTTP Response object.  The parametric response is a Zend Diactoros ```JsonResponse```.

**Middlewares**

To use middlewares in the framework, call ```Sainsburys\HttpService\Application::middlewareManager()```.  You
can add your own implementations of the ```BeforeMiddleware``` and ```AfterMiddleware``` interfaces to a list.  They
will run accordingly.

**Exception Handling**

Throwing an uncaught exception from a controller will cause a response with the exception details encoded in JSON.  The
status code will usually be 500.  If the exception implements
```Sainsburys\HttpService\Components\ErrorHandling\Exceptions\ExceptionWithHttpStatus```, the status code on the
exception will be used.

If you wish to implement your own error handler, for example if you don't want stack traces being visible in the
response in production, call ```Sainsburys\HttpService\Application::useThisErrorController()'``` in your
```index.php``` file, and give it a different error controller it can use.  ```Sainsburys\HttpService\Components\ErrorHandling\ErrorController\ProductionExternalisedErrorController```
is provided if you wish to use an error controller with less verbose output.

**Error Logging**

Your error controller will be passed a ```Psr\Log\LoggerInterface```, which by default will be a
```Psr\Log\NullLogger```.  This does nothing.  To do error logging, call ```Application::setLogger()```.  You will
need a PSR-3 compliant logger for this.  We suggest that you pass your choice of logging object into your other
controllers or other classes as well, where logging is required.  You can do this when you configure the dependencies of
your controller in your DI container.  The [Monolog logging tool](https://github.com/Seldaek/monolog) may be a good
choice of logging library in PHP if you are looking for ideas.

Installation
------------

Use Composer.

```json
"require": {
    "sainsburys/sainsburys-http-service": "*"
}
```

You may also need to require ```"slim/slim": "^3.0.0-RC3"```, depending on your project's composer settings.  This will
change shortly.

Testing
-------

Check the project out, run Composer, and type ```./bin/test``` to run all the tests.  Read
[that shell script](https://github.com/anobii/sainsburys-http-service/blob/master/bin/test) for specific test commands.
 - PHPUnit is used to test Dependency Injection configuration for the framework, and for some integration testing;
 - PHPSpec is used for unit testing;
 - Behat is used to provide behavioural tests, with an example application using the framework, testing the application object inline;
 - A few Behat tests will also run in conjunction with a webserver, with real HTTP requests provided by Guzzle.
