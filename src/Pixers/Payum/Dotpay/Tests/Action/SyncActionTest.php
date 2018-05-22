<?php

namespace Pixers\Payum\Dotpay\Tests\Action;

use Payum\Core\Bridge\Spl\ArrayObject;
use Pixers\Payum\Dotpay\Action\SyncAction;

class SyncActionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function shouldImplementActionInterface()
    {
        $rc = new \ReflectionClass('Pixers\Payum\Dotpay\Action\SyncAction');

        $this->assertTrue($rc->implementsInterface('Payum\Core\Action\ActionInterface'));
    }

    /**
     * @test
     */
    public function shouldBeSubClassOfGatewayAwareAction()
    {
        $rc = new \ReflectionClass('Pixers\Payum\Dotpay\Action\SyncAction');

        $this->assertTrue($rc->isSubclassOf('Payum\Core\Action\GatewayAwareAction'));
    }

    /**
     * @test
     */
    public function shouldNotSupportNotSyncRequest()
    {
        $action = new SyncAction(new ArrayObject());

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
        $action = new SyncAction(new ArrayObject());

        $action->execute(new \stdClass());
    }

}
