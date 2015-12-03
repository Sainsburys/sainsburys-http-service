<?php
namespace SainsburysSpec\Sainsburys\HttpService\Components\ErrorHandling\Exceptions\WithStatusCode;

use Sainsburys\HttpService\Components\ErrorHandling\Exceptions\WithStatusCode\UnknownRoute;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Teapot\StatusCode\Http;

/**
 * @mixin UnknownRoute
 */
class UnknownRouteSpec extends ObjectBehavior
{
    function it_is_initialisable()
    {
        $this->shouldHaveType('\Sainsburys\HttpService\Components\ErrorHandling\Exceptions\WithStatusCode\UnknownRoute');
    }

    function it_has_a_meaningful_message()
    {
        $this->getMessage()->shouldBe('No route configured for request.');
    }

    function it_has_a_404_status_code()
    {
        $statusCode = $this->getHttpStatusCode();

        $statusCode->shouldBe(Http::NOT_FOUND);
        $statusCode->shouldBe(404);
    }
}
