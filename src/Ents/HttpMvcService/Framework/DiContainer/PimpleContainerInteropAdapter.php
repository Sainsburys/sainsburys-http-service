<?php
namespace Ents\HttpMvcService\Framework\DiContainer;

use Interop\Container\ContainerInterface;
use Pimple\Container;

class PimpleContainerInteropAdapter implements ContainerInterface
{
    /** @var Container */
    private $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $serviceId
     * @return mixed
     */
    public function get($serviceId)
    {
        if (!$this->has($serviceId)) {
            throw NotFoundException::constructWithServiceId($serviceId);
        }
        return $this->container[$serviceId];
    }

    /**
     * @param string $serviceId
     * @return bool
     */
    public function has($serviceId)
    {
        return isset($this->container[$serviceId]);
    }
}
