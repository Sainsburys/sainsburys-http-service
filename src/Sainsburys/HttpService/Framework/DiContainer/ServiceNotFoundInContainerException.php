<?php
namespace Sainsburys\HttpService\Framework\DiContainer;

use Interop\Container\Exception\NotFoundException;

class ServiceNotFoundInContainerException extends \RuntimeException implements NotFoundException
{
    /**
     * @param string $serviceId
     * @return ServiceNotFoundInContainerException
     */
    public static function constructWithServiceId($serviceId)
    {
        return new static("Service '$serviceId' not found in DI container");
    }
}
