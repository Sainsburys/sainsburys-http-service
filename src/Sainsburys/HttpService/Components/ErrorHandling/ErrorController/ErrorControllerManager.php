<?php
namespace Sainsburys\HttpService\Components\ErrorHandling\ErrorController;

class ErrorControllerManager
{
    /** @var ErrorController */
    private $errorController;

    public function __construct(ErrorController $errorController)
    {
        $this->errorController = $errorController;
    }

    public function errorController(): ErrorController
    {
        return $this->errorController;
    }

    public function useThisErrorController(ErrorController $errorController)
    {
        $this->errorController = $errorController;
    }
}
