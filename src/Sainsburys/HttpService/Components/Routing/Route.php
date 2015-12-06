<?php
namespace Sainsburys\HttpService\Components\Routing;

use Sainsburys\HttpService\Components\Routing\Exception\InvalidRouteConfigException;

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
     * @param string[] $routeConfigArray
     */
    public function __construct(string $name, array $routeConfigArray)
    {
        $this->setName($name);
        $this->setHttpVerb($routeConfigArray);
        $this->setPathExpression($routeConfigArray);
        $this->setControllerServiceId($routeConfigArray);
        $this->setActionMethodName($routeConfigArray);
    }

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
    private function setHttpVerb(array $routeConfig)
    {
        $this->validateField($routeConfig, 'http-verb');

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
    private function setPathExpression(array $routeConfig)
    {
        $this->validateField($routeConfig, 'path');
        $this->pathExpression = $routeConfig['path'];
    }

    /**
     * Safeguards invariants w.r.t. $this->controllerServiceId
     * @param string[] $routeConfig
     */
    private function setControllerServiceId(array $routeConfig)
    {
        $this->validateField($routeConfig, 'controller-service-id');
        $this->controllerServiceId = $routeConfig['controller-service-id'];
    }

    /**
     * Safeguards invariants w.r.t. $this->actionMethodName
     * @param string[] $routeConfig
     */
    private function setActionMethodName(array $routeConfig)
    {
        $this->validateField($routeConfig, 'action-method-name');
        $this->actionMethodName = $routeConfig['action-method-name'];
    }

    public function httpVerb(): string
    {
        return $this->httpVerb;
    }

    public function pathExpression(): string
    {
        return $this->pathExpression;
    }

    public function controllerServiceId(): string
    {
        return $this->controllerServiceId;
    }

    public function actionMethodName(): string
    {
        return $this->actionMethodName;
    }

    public function name(): string
    {
        return $this->name;
    }

    /**
     * @param string[] $routeConfig
     */
    private function validateField(array $routeConfig, string $fieldName)
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
