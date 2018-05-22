<?php

namespace Pixers\Payum\Dotpay\Action;

use Payum\Core\Reply\HttpResponse;
use Payum\Core\Request\GetHumanStatus;
use Payum\Core\Request\Notify;
use Payum\Core\Action\GatewayAwareAction;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Action\ActionInterface;
use Pixers\Payum\Dotpay\Request\Api\Sync;

/**
 * NotifyAction
 * 
 * @author Michał Kanak <kanakmichal@gmail.com>
 */
class NotifyAction extends GatewayAwareAction implements ActionInterface
{

    /**
     * {@inheritDoc}
     */
    public function execute($request)
    {
        /** @var $request Notify */
        RequestNotSupportedException::assertSupports($this, $request);

        $this->gateway->execute(new Sync($request->getModel()));

        $status = new GetHumanStatus($request->getToken());
        $status->setModel($request->getFirstModel());
        $status->setModel($request->getModel());
        $this->gateway->execute($status);

        throw new HttpResponse('OK', 200);
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
                $request instanceof Notify &&
                $request->getModel() instanceof \ArrayAccess
        ;
    }

}
