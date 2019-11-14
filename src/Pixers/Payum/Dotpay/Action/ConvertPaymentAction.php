<?php

namespace Pixers\Payum\Dotpay\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Model\PaymentInterface;
use Payum\Core\Request\Convert;
use Payum\Core\Exception\InvalidArgumentException;
use Payum\Core\Security\GenericTokenFactory;
use Payum\Core\Security\GenericTokenFactoryAwareInterface;
use Payum\Core\Security\GenericTokenFactoryInterface;
use Pixers\Payum\Dotpay\Constants;

/**
 * ConvertPaymentAction.
 *
 * @author Michał Kanak <kanakmichal@gmail.com>
 */
class ConvertPaymentAction implements ActionInterface, GenericTokenFactoryAwareInterface
{
    /**
     * @var GenericTokenFactory
     */
    protected $tokenFactory;

    /**
     * @param GenericTokenFactoryInterface $genericTokenFactory
     */
    public function setGenericTokenFactory(GenericTokenFactoryInterface $genericTokenFactory = null)
    {
        $this->tokenFactory = $genericTokenFactory;
    }

    /**
     * {@inheritdoc}
     *
     * @param Convert $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var PaymentInterface $payment */
        $payment = $request->getSource();

        $details = ArrayObject::ensureArrayObject($payment->getDetails());

        $this->validateCurrency($payment->getCurrencyCode());

        $details['amount'] = bcdiv($payment->getTotalAmount(), 100); //dotpay format example 12.12 PLN
        $details['currency'] = strtoupper($payment->getCurrencyCode());
        $details['description'] = $payment->getDescription();
        $details['email'] = $payment->getClientEmail();
        $details['URL'] = $request->getToken()->getAfterUrl();
        $details['URLC'] = $this->tokenFactory->createNotifyToken($request->getToken()->getGatewayName(), $request->getToken()->getDetails())
            ->getTargetUrl();

        foreach ($payment->getDetails() as $key => $detail) {
            $details[$key] = $detail;
        }

        $details->defaults([
            Constants::FIELD_STATUS => Constants::STATUS_PENDING,
        ]);

        $request->setResult((array) $details);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
                $request instanceof Convert &&
                $request->getSource() instanceof PaymentInterface &&
                'array' == $request->getTo()
        ;
    }

    /**
     * @param string $currency
     *
     * @throws InvalidArgumentException
     */
    protected function validateCurrency($currency)
    {
        if (!in_array(strtoupper($currency), Constants::getSupportedCurrencies())) {
            throw new InvalidArgumentException("Currency $currency is not supported.", 400);
        }
    }
}
