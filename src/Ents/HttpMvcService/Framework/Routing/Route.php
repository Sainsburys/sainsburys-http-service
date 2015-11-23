<?php
namespace Ents\HttpMvcService\Framework\Routing;

use Ents\HttpMvcService\Framework\Exception\InvalidRouteConfigException;

class Route
{
    /** @var string */
    private $name;

    /** @var string */
    private $httpVerb;

    /** @var string */
    private $pathExpression;

    /** @var string */
    private $controllerServiceId;

    /** @var string */
    private $actionMethodName;

    /**
     * @param string   $name
     * @param string[] $routeConfigArray
     */
    public function __construct($name, $routeConfigArray)
    {
        $this->setName($name);
        $this->setHttpVerb($routeConfigArray);
        $this->setPathExpression($routeConfigArray);
        $this->setControllerServiceId($routeConfigArray);
        $this->setActionMethodName($routeConfigArray);
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        if (!is_string($name) || strlen($name) < 1) {
            throw new InvalidRouteConfigException('Routes in the config file need names');
        }
        $this->name = $name;
    }

    /**
     * Safeguards invariants w.r.t. $this->httpVerb
     * @param string[] $routeConfig
     */
    private function setHttpVerb($routeConfig)
    {
        $this->checkFieldIsPresent($routeConfig, 'http-verb');

        if (!in_array($routeConfig['http-verb'], ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'])) {
            throw new InvalidRouteConfigException(
                "Route '" . $this->name() . "HTTP verb in route config must be one of 'GET', 'POST', 'PUT', 'DELETE', 'PATCH'"
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
        $this->checkFieldIsPresent($routeConfig, 'path');
        $this->pathExpression = $routeConfig['path'];
    }

    /**
     * Safeguards invariants w.r.t. $this->controllerServiceId
     * @param string[] $routeConfig
     */
    private function setControllerServiceId($routeConfig)
    {
        $this->checkFieldIsPresent($routeConfig, 'controller-service-id');
        $this->controllerServiceId = $routeConfig['controller-service-id'];
    }

    /**
     * Safeguards invariants w.r.t. $this->actionMethodName
     * @param string[] $routeConfig
     */
    private function setActionMethodName($routeConfig)
    {
        $this->checkFieldIsPresent($routeConfig, 'action-method-name');
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

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @param string[] $routeConfig
     * @param string   $fieldName
     */
    private function checkFieldIsPresent($routeConfig, $fieldName)
    {
        if (
            !isset($routeConfig[$fieldName]) ||
            !is_string($routeConfig[$fieldName]) ||
            strlen($routeConfig[$fieldName]) < 1
        ) {
            throw new InvalidRouteConfigException(
                "Route '" . $this->name() . "': Must be a valid '$fieldName' in route config"
            );
        }
    }
}
