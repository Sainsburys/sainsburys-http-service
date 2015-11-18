<?php

namespace EntsSpec\Ents\HttpMvcService\Framework;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RouteSpec extends ObjectBehavior
{
    function let()
    {
        $exampleRouteConfig = [
            'http-verb'             => 'GET',
            'path'                  => '/person/:id',
            'controller-service-id' => 'example-controller-service-id',
            'action-method-name'    => 'exampleAction'
        ];
        $this->beConstructedWith($exampleRouteConfig);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Ents\HttpMvcService\Framework\Route');
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
}
