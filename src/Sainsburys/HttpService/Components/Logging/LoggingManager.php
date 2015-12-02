<?php
namespace Sainsburys\HttpService\Components\Logging;

use Psr\Log\LoggerInterface;

class LoggingManager
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
     */
    public function useThisLogger(LoggerInterface $logger)
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
