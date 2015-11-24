<?php
namespace SainsburysSpec\Sainsburys\HttpService\Framework\ErrorHandling;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DefaultErrorControllerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sainsburys\HttpService\Framework\ErrorHandling\DefaultErrorController');
    }

    function it_can_handle_errors()
    {
        // ARRANGE
        $exception = new \Exception('message');

        // ACT
        $result = $this->handleError($exception);

        // ASSERT
        $result->shouldHaveType('\Zend\Diactoros\Response\JsonResponse');
        $result->getStatusCode()->shouldBe(500);
    }
}
