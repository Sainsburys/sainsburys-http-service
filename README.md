[![Circle CI](https://circleci.com/gh/anobii/sainsburys-http-service.svg?style=svg&circle-token=4f6110679c820d7a52903bb0cd6a7d552363cc48)](https://circleci.com/gh/anobii/sainsburys-http-service)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/anobii/sainsburys-http-service/badges/quality-score.png?b=master&s=ad6f751b0f40e1246e30ac7b185b4a3b35c5fcc3)](https://scrutinizer-ci.com/g/anobii/sainsburys-http-service/?branch=master)

![logo](http://www.sainsburys.co.uk/homepage/images/sainsburys.png)

Sainsburys HTTP Service Framework
=================================

PHP Micro-framework for small REST or HTTP RPC services.  Built and open sourced by J Sainsbury plc.

The framework is basically a wrapper for the Slim micro-framework in PHP, but works only with a more structured
application.

It currently relies on Slim 3.0.0-RC2.

Basic Usage
-----------

See the [sample application](https://github.com/anobii/sainsburys-http-service/tree/master/src-dev/sample-application)
for an example of how to use it.  The sample application is used by the automated tests as well, so will be up-to-date.

Core Concepts
-------------

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
container with your controllers in it.  A standards-compliant Pimple 3 wrapper is provided (see
[usage example](https://github.com/anobii/sainsburys-http-service/blob/master/src-dev/sample-application/public/index.php))
.

Try looking at the [example routing file](https://github.com/anobii/sainsburys-http-service/blob/master/src-dev/sample-application/config/routing.php)
and [typical dependency injection configuration](https://github.com/anobii/sainsburys-http-service/blob/master/src-dev/sample-application/src/Sainsburys/HttpService/Dev/MyDiConfig.php)
for a clear example of this.

**Controller Actions - acceptable return types**

Controller actions must return a PSR-7 HTTP Response object.  The parametric response is a Zend Diactoros ```JsonResponse```.

**Middlewares**

To use middlewares in the framework, call ```Sainsburys\HttpService\Application::middlewareManager()```.  You
can add your own implementations of the ```BeforeMiddleware``` and ```AfterMiddleware``` interfaces to a list.  They
will run accordingly.

**Exception Handling**

Throwing an uncaught exception from a controller will cause a response with the exception details encoded in JSON.  The
status code will usually be 500.  If the exception implements
```Sainsburys\HttpService\Components\HttpExceptions\ExceptionWithHttpStatus```, the status code on the exception will be used.

If you wish to implement your own error handler, for example if you don't want stack traces being visible in the
response in production, call ```Sainsburys\HttpService\Application::useThisErrorController()'``` in your
```index.php``` file, and give it a different error controller it can use.

Installation
------------

Use Composer.

```json
"require": {
    "sainsburys/sainsburys-http-service": "*"
}
```

Testing
-------

Check the project out, run Composer, and type ```./bin/test``` to run all the tests.  Read
[that shell script](https://github.com/anobii/sainsburys-http-service/blob/master/bin/test) for specific test commands.
PHPunit is used to test Dependency Injection configuration for the framework.  PHPSpec is used for unit testing, and
Behat is used to test an example application using the framework, in conjunction with a webserver.
