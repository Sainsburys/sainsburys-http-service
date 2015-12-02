<?php
namespace SainsburysSpec\Sainsburys\HttpService\Components\ErrorHandling\ErrorController;

use Psr\Log\LoggerInterface;
use Sainsburys\HttpService\Components\ErrorHandling\ErrorController\ProductionExternalisedErrorController;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin ProductionExternalisedErrorController
 */
class ProductionExternalisedErrorControllerSpec extends ObjectBehavior
{
    function it_is_initialisable()
    {
        $this->shouldHaveType('Sainsburys\HttpService\Components\ErrorHandling\ErrorController\ProductionExternalisedErrorController');
    }

    function it_can_handle_errors(LoggerInterface $logger)
    {
        // ARRANGE
        $exception = new \Exception('message');

        // ACT
        $result = $this->handleError($exception, $logger);

        // ASSERT
        $result->shouldHaveType('\Zend\Diactoros\Response\JsonResponse');
        $result->getStatusCode()->shouldBe(500);
    }

    function it_can_log_errors(LoggerInterface $logger)
    {
        // ARRANGE
        $exception = new \Exception('message');

        // ACT
        $result = $this->handleError($exception, $logger);

        // ASSERT
        $logger->critical('Exception: message', $exception->getTrace())->shouldHaveBeenCalled();
    }
}
