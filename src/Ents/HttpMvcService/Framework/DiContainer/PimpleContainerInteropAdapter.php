<?php
namespace Ents\HttpMvcService\Framework\DiContainer;

use Interop\Container\ContainerInterface;
use Pimple\Container as PimpleContainer;
use Pimple\ServiceProviderInterface;
use Interop\Container\Exception\NotFoundException;

class PimpleContainerInteropAdapter implements ContainerInterface
{
    /** @var PimpleContainer */
    private $pimpleContainer;

    /**
     * @param PimpleContainer|null $pimpleContainer
     */
    public function __construct(PimpleContainer $pimpleContainer = null)
    {
        $this->pimpleContainer = $pimpleContainer ?: new PimpleContainer();
    }

    /**
     * @param ServiceProviderInterface $serviceProvider
     * @return PimpleContainerInteropAdapter
     */
    public static function constructConfiguredWith(ServiceProviderInterface $serviceProvider)
    {
        $container = new static();
        $container->addConfig($serviceProvider);
        return $container;
    }

    /**
     * @param ServiceProviderInterface $pimpleServiceProvider
     */
    public function addConfig(ServiceProviderInterface $pimpleServiceProvider)
    {
        $this->pimpleContainer->register($pimpleServiceProvider);
    }

    /**
     * @throws NotFoundException
     *
     * @param string $serviceId
     * @return mixed
     */
    public function get($serviceId)
    {
        if (!$this->has($serviceId)) {
            throw ServiceNotFoundInContainerException::constructWithServiceId($serviceId);
        }
        return $this->pimpleContainer[$serviceId];
    }

    /**
     * @param string $serviceId
     * @return bool
     */
    public function has($serviceId)
    {
        return isset($this->pimpleContainer[$serviceId]);
    }
}
