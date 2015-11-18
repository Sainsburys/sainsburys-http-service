<?php

namespace EntsSpec\Ents\HttpMvcService\Di;

use PhpSpec\ObjectBehavior;
use Pimple\Container;
use Prophecy\Argument;

class ServiceProviderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Ents\HttpMvcService\Di\ServiceProvider');
    }

    function it_is_registering_things(Container $container)
    {
        $this->register($container);
        $container->offsetSet('ents.http-mvc-service.front-controller', Argument::any())->shouldHaveBeenCalled();
    }
}
