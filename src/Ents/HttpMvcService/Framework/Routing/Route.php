<?php
namespace Ents\HttpMvcService\Framework\Routing;

class Route
{
    /** @var string */
    private $httpVerb;

    /** @var string */
    private $pathExpression;

    /** @var string */
    private $controllerServiceId;

    /** @var string */
    private $actionMethodName;

    /**
     * @param string[] $routeConfigArray
     */
    public function __construct($routeConfigArray)
    {
        $this->httpVerb            = $routeConfigArray['http-verb'];
        $this->pathExpression      = $routeConfigArray['path'];
        $this->controllerServiceId = $routeConfigArray['controller-service-id'];
        $this->actionMethodName    = $routeConfigArray['action-method-name'];
    }

    /**
     * @return string
     */
    public function httpVerb()
    {
        return $this->httpVerb;
    }

    /**
     * @return string
     */
    public function pathExpression()
    {
        return $this->pathExpression;
    }

    /**
     * @return string
     */
    public function controllerServiceId()
    {
        return $this->controllerServiceId;
    }

    /**
     * @return string
     */
    public function actionMethodName()
    {
        return $this->actionMethodName;
    }
}
