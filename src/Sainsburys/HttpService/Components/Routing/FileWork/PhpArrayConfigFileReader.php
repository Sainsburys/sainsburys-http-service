<?php
namespace Sainsburys\HttpService\Components\Routing\FileWork;

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
