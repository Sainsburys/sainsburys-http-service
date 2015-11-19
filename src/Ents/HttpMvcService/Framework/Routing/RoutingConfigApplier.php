<?php
namespace Ents\HttpMvcService\Framework\Routing;

use Ents\HttpMvcService\Framework\Exception\InvalidControllerException;
use Pimple\Container;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Slim\App as SlimApplication;
use Slim\Interfaces\RouterInterface;

class RoutingConfigApplier
{
    /**
     * @param SlimApplication $slimApplication
     * @param Route           $route
     * @param Container       $container
     */
    public function configureApplicationWithRoute(SlimApplication $slimApplication, Route $route, Container $container)
    {
        $controllerCallback = $this->getControllerCallbackForRoute($route, $container, $slimApplication);

        $slimApplication->map(
            [$route->httpVerb()],
            $route->pathExpression(),
            $controllerCallback
        )->setName($route->name());

//        switch ($route->httpVerb()) {
//            case 'GET' :
//                $slimApplication->get($route->pathExpression(), $controllerCallback);
//                break;
//            case 'DELETE' :
//                $slimApplication->delete($route->pathExpression(), $controllerCallback);
//                break;
//            case 'PUT' :
//                $slimApplication->put($route->pathExpression(), $controllerCallback);
//                break;
//            case 'POST' :
//                $slimApplication->post($route->pathExpression(), $controllerCallback);
//                break;
//        }
    }

    /**
     * @param Route     $route
     * @param Container $container
     * @return callable
     */
    private function getControllerCallbackForRoute(Route $route, Container $container, SlimApplication $slimApplication)
    {
        return function ( ) use ($route, $container, $slimApplication) {
//var_dump(func_get_args());exit;
            $controllerServiceId = $route->controllerServiceId();
            $actionMethodName    = $route->actionMethodName();
            $controllerObject    = $container[$controllerServiceId];

            $response = $controllerObject->$actionMethodName($slimApplication->request);

            if (is_array($response)) {
                $response = new Response(json_encode($response));
            }
            if (!$response instanceof ResponseInterface) {
                throw new InvalidControllerException();
            }
return $response;
//            $slimApplication->response()->setBody($response->getBody()->getContents());
//            $slimApplication->response()->setStatus($response->getStatusCode());
//            $slimApplication->response()->headers()->replace($response->getHeaders());
//            $slimApplication->response()->headers()->set('Content-Type', 'application/json');
        };
    }
}
