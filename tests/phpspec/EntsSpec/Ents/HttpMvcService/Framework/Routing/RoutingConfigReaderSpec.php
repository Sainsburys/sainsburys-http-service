<?php
namespace EntsSpec\Ents\HttpMvcService\Framework\Routing;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RoutingConfigReaderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\Ents\HttpMvcService\Framework\Routing\RoutingConfigReader');
    }
}
