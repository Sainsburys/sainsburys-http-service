<?php

namespace SainsburysSpec\Sainsburys\HttpService\Framework\DiContainer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ServiceNotFoundInContainerExceptionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sainsburys\HttpService\Framework\DiContainer\ServiceNotFoundInContainerException');
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
