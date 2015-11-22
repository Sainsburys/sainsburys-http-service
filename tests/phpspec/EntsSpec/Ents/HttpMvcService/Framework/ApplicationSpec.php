<?php
namespace EntsSpec\Ents\HttpMvcService\Framework;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Ents\HttpMvcService\Framework\ErrorHandling\ErrorController;
use Ents\HttpMvcService\Framework\Routing\RoutingConfigApplier;
use Ents\HttpMvcService\Framework\Routing\RoutingConfigReader;
use Slim\App as SlimApplication;

class ApplicationSpec extends ObjectBehavior
{
    function let(
        SlimApplication      $slimApplication,
        RoutingConfigReader  $routingConfigReader,
        RoutingConfigApplier $routingConfigApplier,
        ErrorController      $errorController
    ) {
        $this->beConstructedWith($slimApplication, $routingConfigReader, $routingConfigApplier, $errorController);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Ents\HttpMvcService\Framework\Application');
    }
}
