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
        $container = new Container();
        $this->register($container);

        $container['ents.http-mvc-service.application'];
    }
}
