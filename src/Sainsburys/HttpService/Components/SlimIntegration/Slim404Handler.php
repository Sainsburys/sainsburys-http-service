<?php
namespace Sainsburys\HttpService\Components\SlimIntegration;

use Sainsburys\HttpService\Components\ErrorHandling\Exceptions\WithStatusCode\UnknownRoute;

class Slim404Handler
{
    /**
     * @throws UnknownRoute
     */
    public function __invoke()
    {
        throw new UnknownRoute();
    }
}
