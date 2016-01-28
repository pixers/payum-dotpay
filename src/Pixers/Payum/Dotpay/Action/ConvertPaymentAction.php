<?php

namespace Pixers\Payum\Dotpay\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Model\PaymentInterface;
use Payum\Core\Request\Convert;
use Payum\Core\Exception\InvalidArgumentException;
use Pixers\Payum\Dotpay\Constants;

/**
 * ConvertPaymentAction
 * 
 * @author Michał Kanak <kanakmichal@gmail.com>
 */
class ConvertPaymentAction implements ActionInterface
{

    /**
     * {@inheritDoc}
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

        $details['amount'] = bcdiv((string) $payment->getTotalAmount(), '100', 2); //dotpay format example 12.12 PLN
        $details['currency'] = strtoupper($payment->getCurrencyCode());
        $details['description'] = $payment->getDescription();

        foreach ($payment->getDetails() as $key => $detail) {
            $details[$key] = $detail;
        }

        $details->defaults([
            Constants::FIELD_STATUS => Constants::STATUS_CAPTURED,
        ]);

        $request->setResult((array) $details);
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
                $request instanceof Convert &&
                $request->getSource() instanceof PaymentInterface &&
                $request->getTo() == 'array'
        ;
    }

    /**
     * 
     * @param string $currency
     * @throws InvalidArgumentException
     */
    protected function validateCurrency($currency)
    {
        if (!in_array(strtoupper($currency), Constants::getSupportedCurrencies())) {
            throw new InvalidArgumentException("Currency $currency is not supported.", 400);
        }
    }

}
