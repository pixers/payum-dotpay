<?php

namespace Pixers\Payum\Dotpay\Action;

use Payum\Core\Action\GatewayAwareAction;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Exception\UnsupportedApiException;
use Payum\Core\Request\Capture;
use Pixers\Payum\Dotpay\Request\Api\DoPayment;
use Pixers\Payum\Dotpay\Api;

/**
 * CaptureAction.
 *
 * @author Michał Kanak <kanakmichal@gmail.com>
 */
class CaptureAction extends GatewayAwareAction
{
    /**
     * @var Api
     */
    protected $api;

    /**
     * {@inheritdoc}
     */
    public function setApi($api)
    {
        if (false == $api instanceof Api) {
            throw new UnsupportedApiException('Not supported.');
        }

        $this->api = $api;
    }

    /**
     * {@inheritdoc}
     */
    public function execute($request)
    {
        /* @var $request Capture */
        RequestNotSupportedException::assertSupports($this, $request);

        $this->gateway->execute($doPayment = new DoPayment($request->getModel()));
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
                $request instanceof Capture &&
                $request->getModel() instanceof \ArrayAccess
        ;
    }
}
