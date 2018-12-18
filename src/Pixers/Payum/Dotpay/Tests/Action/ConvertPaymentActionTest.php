<?php

namespace Pixers\Payum\Dotpay\Tests\Action\Api;

use Payum\Core\Model\PaymentInterface;
use Pixers\Payum\Dotpay\Action\ConvertPaymentAction;
use Payum\Core\Model\Payment;
use Payum\Core\Request\Convert;
use Payum\Core\Tests\GenericActionTest;
use Pixers\Payum\Dotpay\Constants;

class ConvertPaymentActionTest extends GenericActionTest
{
    protected $actionClass = 'Pixers\Payum\Dotpay\Action\ConvertPaymentAction';
    protected $requestClass = 'Payum\Core\Request\Convert';

    public function provideSupportedRequests()
    {
        return [
            [new $this->requestClass(new Payment(), 'array')],
            [new $this->requestClass($this->getMock(PaymentInterface::class), 'array')],
            [new $this->requestClass(new Payment(), 'array', $this->getMock('Payum\Core\Security\TokenInterface'))],
        ];
    }

    public function provideNotSupportedRequests()
    {
        return [
            ['foo'],
            [['foo']],
            [new \stdClass()],
            [$this->getMockForAbstractClass('Payum\Core\Request\Generic', [[]])],
            [new $this->requestClass(new \stdClass(), 'array')],
            [new $this->requestClass(new Payment(), 'foobar')],
            [new $this->requestClass($this->getMock(PaymentInterface::class), 'foobar')],
        ];
    }

    /**
     * @test
     */
    public function shouldCorrectlyConvertOrderToDetailsAndSetItBack()
    {
        $order = new Payment();
        $order->setCurrencyCode('PLN');
        $order->setTotalAmount(12312);
        $order->setDescription('the description');

        $action = new ConvertPaymentAction();

        $action->execute($convert = new Convert($order, 'array'));

        $details = $convert->getResult();

        $this->assertNotEmpty($details);

        $this->assertArrayHasKey('amount', $details);
        $this->assertEquals(123.12, round($details['amount'], 2));

        $this->assertArrayHasKey('currency', $details);
        $this->assertEquals('PLN', $details['currency']);

        $this->assertArrayHasKey('description', $details);
        $this->assertEquals('the description', $details['description']);
    }

    /**
     * @test
     */
    public function shouldForcePaidFalseIfAlreadySet()
    {
        $order = new Payment();
        $order->setDetails([
            Constants::FIELD_PAID => false,
        ]);
        $order->setCurrencyCode('PLN');

        $action = new ConvertPaymentAction();

        $action->execute($convert = new Convert($order, 'array'));

        $details = $convert->getResult();

        $this->assertNotEmpty($details);

        $this->assertArrayHasKey(Constants::FIELD_PAID, $details);
        $this->assertEquals(false, $details[Constants::FIELD_PAID]);
    }

    /**
     * @test
     */
    public function shouldNotOverwriteAlreadySetExtraDetails()
    {
        $order = new Payment();
        $order->setCurrencyCode('PLN');
        $order->setTotalAmount(12123);
        $order->setDescription('the description');
        $order->setDetails([
            'foo' => 'fooVal',
        ]);

        $action = new ConvertPaymentAction();

        $action->execute($convert = new Convert($order, 'array'));

        $details = $convert->getResult();

        $this->assertNotEmpty($details);

        $this->assertArrayHasKey('foo', $details);
        $this->assertEquals('fooVal', $details['foo']);
    }
}
