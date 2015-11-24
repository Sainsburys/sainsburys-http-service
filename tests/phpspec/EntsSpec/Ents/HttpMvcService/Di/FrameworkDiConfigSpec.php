<?php
namespace EntsSpec\Ents\HttpMvcService\Di;

use PhpSpec\ObjectBehavior;
use Pimple\Container;
use Prophecy\Argument;

class FrameworkDiConfigSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Ents\HttpMvcService\Di\FrameworkDiConfig');
    }

    function it_can_configure_a_container()
    {
        // ARRANGE
        $container = new Container();
        $this->register($container);

        // ACT
        $application = $container['ents.http-mvc-service.application'];

        // ASSERT
        \PHPUnit_Framework_Assert::assertInstanceOf('\Ents\HttpMvcService\Framework\Application', $application);
    }
}
