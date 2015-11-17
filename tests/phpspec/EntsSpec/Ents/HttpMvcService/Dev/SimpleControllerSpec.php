<?php

namespace EntsSpec\Ents\HttpMvcService\Dev;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SimpleControllerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Ents\HttpMvcService\Dev\SimpleController');
    }

    function it_can_have_a_response_set()
    {
        $this->setResponse('response-body');
        $response = $this->simpleAction();
        $response->shouldHaveType('\Psr\Http\Message\ResponseInterface');
        $response->getBody()->getContents()->shouldBe('response-body');
    }
}
