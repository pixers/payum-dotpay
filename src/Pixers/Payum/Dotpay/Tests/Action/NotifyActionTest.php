<?php

namespace Pixers\Payum\Dotpay\Tests\Action;

use Pixers\Payum\Dotpay\Action\NotifyAction;

class NotifyActionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function shouldImplementActionInterface()
    {
        $rc = new \ReflectionClass('Pixers\Payum\Dotpay\Action\NotifyAction');

        $this->assertTrue($rc->implementsInterface('Payum\Core\Action\ActionInterface'));
    }

    /**
     * @test
     */
    public function couldBeConstructedWithoutAnyArguments()
    {
        new NotifyAction();
    }

    /**
     * @test
     */
    public function shouldNotSupportNotNotifyRequest()
    {
        $action = new NotifyAction();

        $request = new \stdClass();

        $this->assertFalse($action->supports($request));
    }

    /**
     * @test
     *
     * @expectedException \Payum\Core\Exception\RequestNotSupportedException
     */
    public function throwIfNotSupportedRequestGivenAsArgumentForExecute()
    {
        $action = new NotifyAction();

        $action->execute(new \stdClass());
    }

}
