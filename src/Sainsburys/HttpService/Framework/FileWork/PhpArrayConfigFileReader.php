<?php
namespace Sainsburys\HttpService\Framework\FileWork;

class PhpArrayConfigFileReader
{
    /**
     * @param string $path
     * @return array
     */
    public function readConfigFile($path)
    {
        return require $path;
    }
}
