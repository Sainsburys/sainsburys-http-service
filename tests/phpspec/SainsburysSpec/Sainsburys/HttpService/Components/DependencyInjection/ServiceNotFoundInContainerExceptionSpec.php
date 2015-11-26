<?php
namespace SainsburysSpec\Sainsburys\HttpService\Components\DependencyInjection;

use Sainsburys\HttpService\Components\DependencyInjection\ServiceNotFoundInContainerException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin ServiceNotFoundInContainerException
 */
class ServiceNotFoundInContainerExceptionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sainsburys\HttpService\Components\DependencyInjection\ServiceNotFoundInContainerException');
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
