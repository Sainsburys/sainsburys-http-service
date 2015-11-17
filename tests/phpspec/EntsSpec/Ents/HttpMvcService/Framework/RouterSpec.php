<?php
namespace EntsSpec\Ents\HttpMvcService\Framework;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RouterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Ents\HttpMvcService\Framework\Router');
    }
}
