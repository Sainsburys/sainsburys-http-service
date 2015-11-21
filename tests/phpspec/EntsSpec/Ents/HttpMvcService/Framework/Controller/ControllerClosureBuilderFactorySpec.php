<?php

namespace EntsSpec\Ents\HttpMvcService\Framework\Controller;

use Ents\HttpMvcService\Framework\ErrorHandling\ErrorController;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ControllerClosureBuilderFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Ents\HttpMvcService\Framework\Controller\ControllerClosureBuilderFactory');
    }

    function it_can_create_a_controller_closure_builder(ErrorController $errorController)
    {
        $this
            ->getControllerClosureBuilder($errorController)
            ->shouldHaveType('\Ents\HttpMvcService\Framework\Controller\ControllerClosureBuilder\ErrorHandlingDecorator');
    }
}
