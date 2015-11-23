<?php

namespace EntsSpec\Ents\HttpMvcService\Framework\DiContainer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NotFoundExceptionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Ents\HttpMvcService\Framework\DiContainer\ServiceNotFoundInContainerException');
    }

    function it_is_standards_compliant()
    {
        $this->shouldHaveType('\Interop\Container\Exception\NotFoundException');
    }

    function it_has_a_nice_named_constructor()
    {
        $this->beConstructedThrough('constructWithServiceId', ['service-id']);
        $this->getMessage()->shouldBe("Service 'service-id' not found in DI container");
    }
}
