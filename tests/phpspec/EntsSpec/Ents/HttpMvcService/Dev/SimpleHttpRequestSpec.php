<?php
namespace EntsSpec\Ents\HttpMvcService\Dev;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SimpleHttpRequestSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Ents\HttpMvcService\Dev\SimpleHttpRequest');
    }
}
