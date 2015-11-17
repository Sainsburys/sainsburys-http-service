<?php

namespace EntsSpec\Ents\HttpMvcService\Framework;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FrontControllerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Ents\HttpMvcService\Framework\FrontController');
    }
}
