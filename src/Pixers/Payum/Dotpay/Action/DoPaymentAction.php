<?php

namespace Pixers\Payum\Dotpay\Action;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Reply\HttpPostRedirect;
use Payum\Core\Reply\HttpRedirect;
use Pixers\Payum\Dotpay\Request\Api\DoPayment;
use Pixers\Payum\Dotpay\Action\Api\BaseApiAwareAction;

/**
 * DoPaymentAction
 *
 * @author Michał Kanak <kanakmichal@gmail.com>
 */
class DoPaymentAction extends BaseApiAwareAction
{

    /**
     * {@inheritDoc}
     */
    public function execute($request)
    {
        /** @var $request DoPayment */
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $this->doPayment((array) $model);
    }

    /**
     * @param array $fields
     */
    protected function doPayment(array $fields)
    {
        $this->addMethodField($fields);
        $this->addAuthFields($fields);

        $this->doPaymentRedirect($fields);
    }

    /**
     * @param array $fields
     * 
     * @throw HttpPostRedirect|HttpRedirect
     */
    protected function doPaymentRedirect(array $fields)
    {

        if ($fields['method'] == 'POST') {
            throw new HttpPostRedirect($this->api->getApiOptions()['endpoint'], $fields);
        }
        
        //GET
        throw new HttpRedirect($this->api->getApiOptions()['endpoint'] . '?' . http_build_query($fields));
    }

    /**
     *
     * @param array $fields
     */
    protected function addMethodField(array &$fields)
    {
        if (!isset($fields['method']) || !in_array(strtoupper($fields['method']), ['POST', 'GET'])) {
            $fields['method'] = strtoupper($this->api->getApiOptions()['method']);
        }
    }

    /**
     *
     * @param array $fields
     */
    protected function addAuthFields(array &$fields)
    {
        $fields['id'] = $this->api->getApiOptions()['id'];
        $fields['url'] = (isset($fields['url'])) ? $fields['url'] : $this->api->getApiOptions()['url'];
        $fields['URLC'] = (isset($fields['URLC'])) ? $fields['URLC'] : $this->api->getApiOptions()['URLC'];
        $fields['type'] = (isset($fields['type'])) ? $fields['type'] : $this->api->getApiOptions()['type'];
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
                $request instanceof DoPayment &&
                $request->getModel() instanceof \ArrayAccess
        ;
    }

}
