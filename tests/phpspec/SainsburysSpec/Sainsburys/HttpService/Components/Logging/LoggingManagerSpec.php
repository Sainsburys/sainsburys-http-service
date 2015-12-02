<?php
namespace SainsburysSpec\Sainsburys\HttpService\Components\Logging;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Sainsburys\HttpService\Components\Logging\LoggingManager;

/**
 * @mixin LoggingManager
 */
class LoggingManagerSpec extends ObjectBehavior
{
    function let(LoggerInterface $logger)
    {
        $this->beConstructedWith($logger);
    }

    function it_is_initialisable()
    {
        $this->shouldHaveType('Sainsburys\HttpService\Components\Logging\LoggingManager');
    }

    function it_is_standards_compliant()
    {
        $this->shouldHaveType('Psr\Log\LoggerAwareInterface');
    }

    function it_can_accept_a_new_logger(LoggerInterface $logger, LoggerInterface $anotherLogger)
    {
        $this->logger()->shouldBe($logger);
        $this->setLogger($anotherLogger);
        $this->logger()->shouldBe($anotherLogger);
    }
}
