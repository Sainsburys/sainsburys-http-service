<?php
namespace Ents\HttpMvcService\Framework\DiContainer;

class NotFoundException extends \RuntimeException
{
    /**
     * @param string $serviceId
     * @return NotFoundException
     */
    public static function constructWithServiceId($serviceId)
    {
        return new static("Service '$serviceId' not found in DI container");
    }
}
