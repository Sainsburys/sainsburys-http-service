<?php
namespace SainsburysSpec\Sainsburys\HttpService\Components\ErrorHandling\ErrorController;

use Sainsburys\HttpService\Components\ErrorHandling\ErrorController\ErrorController;
use Sainsburys\HttpService\Components\ErrorHandling\ErrorController\ErrorControllerManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin ErrorControllerManager
 */
class ErrorControllerManagerSpec extends ObjectBehavior
{
    function let(ErrorController $errorController)
    {
        $this->beConstructedWith($errorController);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sainsburys\HttpService\Components\ErrorHandling\ErrorController\ErrorControllerManager');
    }

    function it_can_take_a_new_error_controller(ErrorController $errorController, ErrorController $anotherErrorController)
    {
        $this->errorController()->shouldBe($errorController);
        $this->useThisErrorController($anotherErrorController);
        $this->errorController()->shouldBe($anotherErrorController);
    }
}
