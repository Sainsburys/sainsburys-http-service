<?php
namespace EntsSpec\Ents\HttpMvcService\Framework\Exception\WithStatusCode;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Teapot\StatusCode\Http;

class NotFoundExceptionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('\Ents\HttpMvcService\Framework\Exception\WithStatusCode\NotFoundException');
    }

    function it_has_a_meaningful_message()
    {
        $this->getMessage()->shouldBe('Resource or endpoint not found.');
    }

    function it_has_a_404_status_code()
    {
        $statusCode = $this->getHttpStatusCode();

        $statusCode->shouldBe(Http::NOT_FOUND);
        $statusCode->shouldBe(404);
    }
}
