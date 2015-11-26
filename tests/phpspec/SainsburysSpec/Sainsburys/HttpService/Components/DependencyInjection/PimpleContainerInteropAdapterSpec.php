<?php
namespace SainsburysSpec\Sainsburys\HttpService\Components\DependencyInjection;

use Sainsburys\HttpService\Components\DependencyInjection\PimpleContainerInteropAdapter;
use Sainsburys\HttpService\Dev\Controller\SimpleController;
use PhpSpec\ObjectBehavior;
use Pimple\ServiceProviderInterface;
use Prophecy\Argument;
use Pimple\Container as PimpleContainer;

/**
 * @mixin PimpleContainerInteropAdapter
 */
class PimpleContainerInteropAdapterSpec extends ObjectBehavior
{
    function let(PimpleContainer $pimpleContainer)
    {
        $this->beConstructedWith($pimpleContainer);
    }

    function it_is_initialisable()
    {
        $this->shouldHaveType('Sainsburys\HttpService\Components\DependencyInjection\PimpleContainerInteropAdapter');
    }

    function it_is_standards_compliant()
    {
        $this->shouldHaveType('Interop\Container\ContainerInterface');
    }

    function it_can_tell_you_if_it_has_a_service(PimpleContainer $pimpleContainer)
    {
        $pimpleContainer->offsetExists('service-id-that-exists')->willReturn(true);
        $pimpleContainer->offsetExists('service-id-that-doesnt-exist')->willReturn(false);

        $this->has('service-id-that-exists')->shouldBe(true);
        $this->has('service-id-that-doesnt-exist')->shouldBe(false);
    }

    function it_can_get_things(PimpleContainer $pimpleContainer, SimpleController $serviceFromContainer)
    {
        $pimpleContainer->offsetExists('service-id')->willReturn(true);
        $pimpleContainer->offsetGet('service-id')->willReturn($serviceFromContainer);

        $this->get('service-id')->shouldHaveType('\Sainsburys\HttpService\Dev\Controller\SimpleController');
    }

    function it_throws_a_standards_compliant_exception_if_the_service_doesnt_exist(PimpleContainer $pimpleContainer)
    {
        $pimpleContainer->offsetExists('service-id')->willReturn(false);
        $this->shouldThrow('\Interop\Container\Exception\NotFoundException')->during('get', ['service-id']);
    }

    function it_can_add_configs_to_the_thing_its_a_wrapper_for(PimpleContainer $pimpleContainer, ServiceProviderInterface $pimpleServiceProvider)
    {
        $this->addConfig($pimpleServiceProvider);
        $pimpleContainer->register($pimpleServiceProvider)->shouldHaveBeenCalled();
    }

    function it_has_a_nice_named_constructor(ServiceProviderInterface $pimpleServiceProvider)
    {
        $this->beConstructedThrough('constructConfiguredWith', [$pimpleServiceProvider]);
        $this->shouldHaveType('Sainsburys\HttpService\Components\DependencyInjection\PimpleContainerInteropAdapter');
    }
}
