![logo](http://northcyprusfreepress.com/wp-content/uploads/2014/09/sainsburys-entertainment.png)

HTTP MVC Service framework
==========================

PHP Micro-framework for small REST or HTTP RPC services

Basic Usage
-----------

See the [sample application](https://github.com/anobii/http-mvc-service/tree/master/src-dev/sample-application) for an example of how to use it.  The sample application is used by the automated tests as well, so will be up-to-date.

Core Concept
------------

The framework is basically a wrapper for the Slim micro-framework in PHP, but works only with a more structured application.

**Controllers and Dependency Injection**

Controllers must be objects, not closures.  No abstract controller is provided - controllers should be stand-alone objects with no inheritance.  Controllers will not be given access to the service container, and must use proper dependency injection - service location won't work here.

Your routing config will map a path to the service ID of the controller, as defined in your dependency injection configuration.  (Only Pimple is supported ATOW, but Container Interop may be added later.)

Try looking at the [example routing file](https://github.com/anobii/http-mvc-service/blob/master/src-dev/sample-application/config/routing.php) and [typical dependency injection configuration](https://github.com/anobii/http-mvc-service/blob/master/src-dev/sample-application/src/Ents/HttpMvcService/Dev/DiServiceProvider.php) for a clear example of this.

**Controller Actions - acceptable return types**

Controller actions must return either a PSR-7 HTTP Response object, or an array.  If an array is returned, it will be ```json_encode()```ed, and the status code on the response will be 200.  If you can't decide what PSR-7 implementation to use, the Zend Diactoros ```JsonResponse``` class would be a reasonable choice.  Any PSR-7 implementation should work.

Installation
------------

Use Composer.

Testing
-------

Check the project out, run Composer, and type ```./bin/test``` to run all the tests.
