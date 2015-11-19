<?php
namespace Ents\HttpMvcService\Framework\Routing;

use Ents\HttpMvcService\Framework\Exception\InvalidRouteConfigException;

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
        $this->setHttpVerb($routeConfigArray);
        $this->setPathExpression($routeConfigArray);
        $this->setControllerServiceId($routeConfigArray);
        $this->setActionMethodName($routeConfigArray);
    }

    /**
     * Safeguards invariants w.r.t. $this->httpVerb
     * @param string[] $routeConfig
     */
    private function setHttpVerb($routeConfig)
    {
        if (!isset($routeConfig['http-verb'])) {
            throw new InvalidRouteConfigException('"http-verb" not found in config for route');
        }
        if (!in_array($routeConfig['http-verb'], ['GET', 'POST', 'PUT', 'DELETE'])) {
            throw new InvalidRouteConfigException(
                "HTTP verb in route config must be one of 'GET', 'POST', 'PUT', 'DELETE'"
            );
        }
        $this->httpVerb = $routeConfig['http-verb'];
    }

    /**
     * Safeguards invariants w.r.t. $this->pathExpression
     * @param string[] $routeConfig
     */
    private function setPathExpression($routeConfig)
    {
        if (
            !isset($routeConfig['path']) ||
            !is_string($routeConfig['path']) ||
            strlen($routeConfig['path']) < 1
        ) {
            throw new InvalidRouteConfigException("Must be a valid 'path' in route config");
        }
        $this->pathExpression = $routeConfig['path'];
    }

    /**
     * Safeguards invariants w.r.t. $this->controllerServiceId
     * @param string[] $routeConfig
     */
    private function setControllerServiceId($routeConfig)
    {
        if (
            !isset($routeConfig['controller-service-id']) ||
            !is_string($routeConfig['controller-service-id']) ||
            strlen($routeConfig['controller-service-id']) < 1
        ) {
            throw new InvalidRouteConfigException("Must be a valid 'controller-service-id' in route config");
        }
        $this->controllerServiceId = $routeConfig['controller-service-id'];
    }

    /**
     * Safeguards invariants w.r.t. $this->actionMethodName
     * @param string[] $routeConfig
     */
    private function setActionMethodName($routeConfig)
    {
        if (
            !isset($routeConfig['action-method-name']) ||
            !is_string($routeConfig['action-method-name']) ||
            strlen($routeConfig['action-method-name']) < 1
        ) {
            throw new InvalidRouteConfigException("Must be a valid 'action-method-name' in route config");
        }
        $this->actionMethodName = $routeConfig['action-method-name'];
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
