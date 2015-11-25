<?php
namespace Sainsburys\HttpService\Components\ErrorHandling;

class ErrorControllerManager
{
    /** @var ErrorController */
    private $errorController;

    /**
     * @param ErrorController $errorController
     */
    public function __construct(ErrorController $errorController)
    {
        $this->errorController = $errorController;
    }

    /**
     * @return ErrorController
     */
    public function errorController()
    {
        return $this->errorController;
    }

    /**
     * @param ErrorController $errorController
     */
    public function useThisErrorController(ErrorController $errorController)
    {
        $this->errorController = $errorController;
    }
}
