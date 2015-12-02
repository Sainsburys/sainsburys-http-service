<?php
namespace SainsburysSpec\Sainsburys\HttpService\Misc;

use Sainsburys\HttpService\Misc\DiConfig;
use PhpSpec\ObjectBehavior;
use Pimple\Container;
use Prophecy\Argument;

/**
 * @mixin DiConfig
 */
class DiConfigSpec extends ObjectBehavior
{
    function it_is_initialisable()
    {
        $this->shouldHaveType('Sainsburys\HttpService\Misc\DiConfig');
    }

    function it_can_configure_a_container()
    {
        // ARRANGE
        $container = new Container();
        $this->register($container);

        // ACT
        $application = $container['sainsburys.sainsburys-http-service.application'];

        // ASSERT
        \PHPUnit_Framework_Assert::assertInstanceOf('\Sainsburys\HttpService\Application', $application);
    }
}
