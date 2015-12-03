<?php
namespace Sainsburys\HttpService\Components\Logging;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

class LoggingManager implements LoggerAwareInterface
{
    /** @var LoggerInterface */
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param LoggerInterface $logger
     * @return null
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return LoggerInterface
     */
    public function logger()
    {
        return $this->logger;
    }
}
