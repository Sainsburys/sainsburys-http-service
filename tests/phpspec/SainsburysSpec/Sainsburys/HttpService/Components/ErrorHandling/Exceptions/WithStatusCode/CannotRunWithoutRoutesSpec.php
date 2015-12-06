<?php
namespace SainsburysSpec\Sainsburys\HttpService\Components\ErrorHandling\Exceptions\WithStatusCode;

use Sainsburys\HttpService\Components\ErrorHandling\Exceptions\WithStatusCode\CannotRunWithoutRoutes;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Teapot\StatusCode\Http;

/**
 * @mixin CannotRunWithoutRoutes
 */
class CannotRunWithoutRoutesSpec extends ObjectBehavior
{
    function it_is_initialisable()
    {
        $this->shouldHaveType('\Sainsburys\HttpService\Components\ErrorHandling\Exceptions\WithStatusCode\CannotRunWithoutRoutes');
    }

    function it_has_a_meaningful_message()
    {
        $this->getMessage()->shouldBe('Must call takeRoutingConfigs() before run().  Try using Application::factory() to create the Application');
    }

    function it_has_a_500_status_code()
    {
        $statusCode = $this->getHttpStatusCode();

        $statusCode->shouldBe(Http::INTERNAL_SERVER_ERROR);
        $statusCode->shouldBe(500);
    }
}
