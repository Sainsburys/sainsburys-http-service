<?php
namespace SainsburysSpec\Sainsburys\HttpService\Framework\Exception\Framework;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Teapot\StatusCode\Http;

class InvalidRouteConfigExceptionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sainsburys\HttpService\Framework\Exception\Framework\InvalidRouteConfigException');
    }

    function it_has_a_500_status_code()
    {
        $statusCode = $this->getHttpStatusCode();

        $statusCode->shouldBe(Http::INTERNAL_SERVER_ERROR);
        $statusCode->shouldBe(500);
    }
}
