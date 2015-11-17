<?php

namespace EntsSpec\Ents\HttpMvcService\Di;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ServiceProviderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Ents\HttpMvcService\Di\ServiceProvider');
    }
}
