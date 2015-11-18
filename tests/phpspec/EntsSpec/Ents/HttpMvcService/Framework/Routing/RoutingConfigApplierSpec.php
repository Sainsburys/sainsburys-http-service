<?php

namespace EntsSpec\Ents\HttpMvcService\Framework\Routing;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RoutingConfigApplierSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Ents\HttpMvcService\Framework\Routing\RoutingConfigApplier');
    }
}
