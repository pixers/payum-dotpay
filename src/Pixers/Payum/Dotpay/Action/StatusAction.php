<?php

namespace Pixers\Payum\Dotpay\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\GetStatusInterface;
use Pixers\Payum\Dotpay\Constants;

/**
 * StatusAction
 * 
 * @author Michał Kanak <kanakmichal@gmail.com>
 */
class StatusAction implements ActionInterface
{

    /**
     * {@inheritDoc}
     */
    public function execute($request)
    {
        /** @var $request GetStatusInterface */
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        if (false == $model[Constants::FIELD_STATUS] || $model[Constants::FIELD_STATUS] == Constants::STATUS_NEW) {
            $request->markNew();

            return;
        }

        if (Constants::STATUS_PENDING == $model[Constants::FIELD_STATUS]) {
            $request->markPending();

            return;
        }

        if (Constants::STATUS_CAPTURED == $model[Constants::FIELD_STATUS]) {
            $request->markCaptured();

            return;
        }

        if (Constants::STATUS_AUTHORIZED == $model[Constants::FIELD_STATUS]) {
            $request->markAuthorized();

            return;
        }

        if (Constants::STATUS_FAILED == $model[Constants::FIELD_STATUS]) {
            $request->markFailed();

            return;
        }

        if (Constants::STATUS_CANCELED == $model[Constants::FIELD_STATUS]) {
            $request->markCanceled();

            return;
        }

        if (Constants::STATUS_REFUNDED == $model[Constants::FIELD_STATUS]) {
            $request->markRefunded();

            return;
        }

        $request->markUnknown();
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
                $request instanceof GetStatusInterface &&
                $request->getModel() instanceof \ArrayAccess
        ;
    }

}
