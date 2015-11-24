[![Circle CI](https://circleci.com/gh/anobii/sainsburys-http-service.svg?style=svg&circle-token=4f6110679c820d7a52903bb0cd6a7d552363cc48)](https://circleci.com/gh/anobii/sainsburys-http-service)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/anobii/sainsburys-http-service/badges/quality-score.png?b=master&s=ad6f751b0f40e1246e30ac7b185b4a3b35c5fcc3)](https://scrutinizer-ci.com/g/anobii/sainsburys-http-service/?branch=master)

![logo](http://www.sainsburys.co.uk/homepage/images/sainsburys.png)

Sainsburys HTTP Service Framework
=================================

PHP Micro-framework for small REST or HTTP RPC services.  Built and open sourced by J Sainsbury plc.

Basic Usage
-----------

See the [sample application](https://github.com/anobii/sainsburys-http-service/tree/master/src-dev/sample-application) for an
example of how to use it.  The sample application is used by the automated tests as well, so will be up-to-date.

Core Concept
------------

The framework is basically a wrapper for the Slim micro-framework in PHP, but works only with a more structured
application.

**Controllers and Dependency Injection**

Controllers must be objects, not closures.  No abstract controller is provided - controllers should be stand-alone
objects with no inheritance.  Controllers will not be given access to the service container, and must use proper
dependency injection - service location won't work here.

Your routing config will map a path to the service ID of the controller, as defined in your dependency injection
configuration.  You should use a Container Interop dependency injection container - a standards-compliant Pimple wrapper
is provided (see [usage example](https://github.com/anobii/sainsburys-http-service/blob/master/src-dev/sample-application/public/index.php)).

Try looking at the [example routing file](https://github.com/anobii/sainsburys-http-service/blob/master/src-dev/sample-application/config/routing.php)
and [typical dependency injection configuration](https://github.com/anobii/sainsburys-http-service/blob/master/src-dev/sample-application/src/Sainsburys/HttpService/Dev/DiServiceProvider.php)
for a clear example of this.

**Controller Actions - acceptable return types**

Controller actions must return a PSR-7 HTTP Response object.  If you can't decide what PSR-7 implementation to use, the
Zend Diactoros ```JsonResponse``` class would be a reasonable choice.  Any PSR-7 implementation should work.

**Exception Handling**

Throwing an uncaught exception from a controller will cause a response with the exception details encoded in JSON.  The
status code will usually be 500.  If the exception implements
```Sainsburys\HttpService\Framework\Exception\ExceptionWithHttpStatus```, the status code on the exception will be used.

If you wish to implement your own error handler, for example if you don't want stack traces being visible in the
response in production, call ```Sainsburys\HttpService\Framework\Application::setErrorHandler()'``` in your ```index.php```
file, and give it a different error controller it can use.

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
