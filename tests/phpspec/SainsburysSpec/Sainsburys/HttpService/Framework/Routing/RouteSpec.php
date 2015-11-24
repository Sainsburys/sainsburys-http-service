<?php
namespace SainsburysSpec\Sainsburys\HttpService\Framework\Routing;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RouteSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('route-name', $this->getValidConfigArray());
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('\Sainsburys\HttpService\Framework\Routing\Route');
    }

    function it_can_return_the_http_verb()
    {
        $this->httpVerb()->shouldBe('GET');
    }

    function it_can_return_a_path_expression()
    {
        $this->pathExpression()->shouldBe('/person/:id');
    }

    function it_can_return_the_controller_service_id()
    {
        $this->controllerServiceId()->shouldBe('example-controller-service-id');
    }

    function it_can_return_the_action_method_name()
    {
        $this->actionMethodName()->shouldBe('exampleAction');
    }

    function it_can_return_the_route_name()
    {
        $this->name()->shouldBe('route-name');
    }

    function it_always_has_a_path()
    {
        $configArray = $this->getValidConfigArray();
        $configArray['path'] = '';
        $this->beConstructedWith('route-name', $configArray);

        $this
            ->shouldThrow('Sainsburys\HttpService\Framework\Exception\Framework\InvalidRouteConfigException')
            ->duringInstantiation();
    }

    function it_always_has_a_controller_service_id()
    {
        $configArray = $this->getValidConfigArray();
        $configArray['controller-service-id'] = '';
        $this->beConstructedWith('route-name', $configArray);

        $this
            ->shouldThrow('Sainsburys\HttpService\Framework\Exception\Framework\InvalidRouteConfigException')
            ->duringInstantiation();
    }

    function it_always_has_an_action_method_name()
    {
        $configArray = $this->getValidConfigArray();
        $configArray['action-method-name'] = '';
        $this->beConstructedWith('route-name', $configArray);

        $this
            ->shouldThrow('Sainsburys\HttpService\Framework\Exception\Framework\InvalidRouteConfigException')
            ->duringInstantiation();
    }

    function it_always_has_a_sensible_http_verb()
    {
        $configArray = $this->getValidConfigArray();
        $configArray['http-verb'] = 'NOTANHTTPVERB';
        $this->beConstructedWith('route-name', $configArray);

        $this
            ->shouldThrow('Sainsburys\HttpService\Framework\Exception\Framework\InvalidRouteConfigException')
            ->duringInstantiation();
    }

    function it_always_has_a_name()
    {
        $configArray = $this->getValidConfigArray();
        $this->beConstructedWith('', $configArray);

        $this
            ->shouldThrow('Sainsburys\HttpService\Framework\Exception\Framework\InvalidRouteConfigException')
            ->duringInstantiation();
    }

    /**
     * @return string[]
     */
    private function getValidConfigArray()
    {
        return [
            'http-verb'             => 'GET',
            'path'                  => '/person/:id',
            'controller-service-id' => 'example-controller-service-id',
            'action-method-name'    => 'exampleAction'
        ];
    }
}
