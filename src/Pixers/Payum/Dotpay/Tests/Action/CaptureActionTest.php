<?php

namespace Pixers\Payum\Dotpay\Tests\Action;

use Pixers\Payum\Dotpay\Action\CaptureAction;
use Payum\Core\Request\Capture;

class CaptureActionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldImplementActionInterface()
    {
        $rc = new \ReflectionClass('Pixers\Payum\Dotpay\Action\CaptureAction');

        $this->assertTrue($rc->implementsInterface('Payum\Core\Action\ActionInterface'));
    }

    /**
     * @test
     */
    public function couldBeConstructedWithoutAnyArguments()
    {
        new CaptureAction();
    }

    /**
     * @test
     */
    public function shouldSupportCaptureWithArrayAccessAsModel()
    {
        $action = new CaptureAction();

        $request = new Capture($this->getMock('ArrayAccess'));

        $this->assertTrue($action->supports($request));
    }

    /**
     * @test
     */
    public function shouldNotSupportNotCapture()
    {
        $action = new CaptureAction();

        $request = new \stdClass();

        $this->assertFalse($action->supports($request));
    }

    /**
     * @test
     */
    public function shouldNotSupportCaptureAndNotArrayAccessAsModel()
    {
        $action = new CaptureAction();

        $request = new Capture(new \stdClass());

        $this->assertFalse($action->supports($request));
    }
}
