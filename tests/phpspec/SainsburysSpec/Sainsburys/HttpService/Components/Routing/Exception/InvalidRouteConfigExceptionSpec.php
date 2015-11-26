<?php
namespace SainsburysSpec\Sainsburys\HttpService\Components\Routing\Exception;

use Sainsburys\HttpService\Components\Routing\Exception\InvalidRouteConfigException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Teapot\StatusCode\Http;

/**
 * @mixin InvalidRouteConfigException
 */
class InvalidRouteConfigExceptionSpec extends ObjectBehavior
{
    function it_is_initialisable()
    {
        $this->shouldHaveType('Sainsburys\HttpService\Components\Routing\Exception\InvalidRouteConfigException');
    }

    function it_has_a_500_status_code()
    {
        $statusCode = $this->getHttpStatusCode();

        $statusCode->shouldBe(Http::INTERNAL_SERVER_ERROR);
        $statusCode->shouldBe(500);
    }
}
