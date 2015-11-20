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

Controllers *must* be stand-alone classes - no abstract controller class is provided, and the use of inheritance in user-land code with controllers is strongly discouraged: use composition instead.  Your controller cannot have access to a service container - service location won't work here.  You should configure your controller's object graph using a Pimple DI container, ideally with the ```ServiceProviderInterface``` provided by Pimple.  You must then pass your container into the framework, and it will retrieve controller objects, complete with all dependencies, when they are needed.

Try looking at the [example routing file](https://github.com/anobii/http-mvc-service/blob/master/src-dev/sample-application/config/routing.php) and [typical dependency injection configuration](https://github.com/anobii/http-mvc-service/blob/master/src-dev/sample-application/src/Ents/HttpMvcService/Dev/DiServiceProvider.php) to understand this.

Testing
-------

Check the project out, run Composer, and type ```./bin/test``` to run all the tests.
