<?php
namespace SainsburysSpec\Sainsburys\HttpService\Components\ErrorHandling\Exceptions\WithStatusCode;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Teapot\StatusCode\Http;

class NotAuthorisedExceptionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\Sainsburys\HttpService\Components\ErrorHandling\Exceptions\WithStatusCode\NotAuthorisedException');
    }

    function it_has_a_meaningful_message()
    {
        $this->getMessage()->shouldBe('Access to resource is not authorised.');
    }

    function it_has_a_401_status_code()
    {
        $statusCode = $this->getHttpStatusCode();

        $statusCode->shouldBe(Http::UNAUTHORIZED);
        $statusCode->shouldBe(401);
    }
}
