<?php

namespace SainsburysSpec\Sainsburys\HttpService\Components\SlimIntegration;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class Slim404HandlerSpec extends ObjectBehavior
{
    function it_is_initialisable()
    {
        $this->shouldHaveType('\Sainsburys\HttpService\Components\SlimIntegration\Slim404Handler');
    }

    function it_throws_a_404_exception()
    {
        $this
            ->shouldThrow('Sainsburys\HttpService\Components\ErrorHandling\Exceptions\WithStatusCode\UnknownRoute')
            ->during('__invoke');
    }
}
