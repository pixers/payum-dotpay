<?php

namespace Pixers\Payum\Dotpay\Action;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Reply\HttpPostRedirect;
use Payum\Core\Reply\HttpRedirect;
use Pixers\Payum\Dotpay\Constants;
use Pixers\Payum\Dotpay\Request\Api\DoPayment;
use Pixers\Payum\Dotpay\Action\Api\BaseApiAwareAction;

/**
 * DoPaymentAction.
 *
 * @author Michał Kanak <kanakmichal@gmail.com>
 */
class DoPaymentAction extends BaseApiAwareAction
{
    /**
     * {@inheritdoc}
     */
    public function execute($request)
    {
        /* @var $request DoPayment */
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
        $method = $fields['method'];
        unset($fields['method']);
        unset($fields[Constants::FIELD_STATUS]);

        $this->addChkField($fields);

        if ('POST' === $method) {
            throw new HttpPostRedirect($this->api->getApiOptions()['endpoint'], $fields);
        }

        //GET
        throw new HttpRedirect($this->api->getApiOptions()['endpoint'].'?'.http_build_query($fields));
    }

    /**
     * @param array $fields
     */
    protected function addMethodField(array &$fields)
    {
        if (!isset($fields['method']) || !in_array(strtoupper($fields['method']), ['POST', 'GET'])) {
            $fields['method'] = strtoupper($this->api->getApiOptions()['method']);
        }
    }

    /**
     * @param array $fields
     */
    protected function addAuthFields(array &$fields)
    {
        $fields['id'] = $this->api->getApiOptions()['id'];
        $fields['URL'] = (isset($fields['URL'])) ? $fields['URL'] : $this->api->getApiOptions()['URL'];
        $fields['URLC'] = (isset($fields['URLC'])) ? $fields['URLC'] : $this->api->getApiOptions()['URLC'];
        $fields['type'] = (isset($fields['type'])) ? $fields['type'] : $this->api->getApiOptions()['type'];
        $fields['api_version'] = (isset($fields['api_version'])) ? $fields['api_version'] : $this->api->getApiOptions()['api_version'];
    }

    protected function addChkField(array &$fields)
    {
        $string = $this->api->getApiOptions()['PIN'].
            (isset($fields['api_version']) ? $fields['api_version'] : null).
            (isset($fields['charset']) ? $fields['charset'] : null).
            (isset($fields['lang']) ? $fields['lang'] : null).
            (isset($fields['id']) ? $fields['id'] : null).
            (isset($fields['amount']) ? $fields['amount'] : null).
            (isset($fields['currency']) ? $fields['currency'] : null).
            (isset($fields['description']) ? $fields['description'] : null).
            (isset($fields['control']) ? $fields['control'] : null).
            (isset($fields['channel']) ? $fields['channel'] : null).
            (isset($fields['credit_card_brand']) ? $fields['credit_card_brand'] : null).
            (isset($fields['ch_lock']) ? $fields['ch_lock'] : null).
            (isset($fields['channel_groups']) ? $fields['channel_groups'] : null).
            (isset($fields['onlinetransfer']) ? $fields['onlinetransfer'] : null).
            (isset($fields['URL']) ? $fields['URL'] : null).
            (isset($fields['type']) ? $fields['type'] : null).
            (isset($fields['buttontext']) ? $fields['buttontext'] : null).
            (isset($fields['URLC']) ? $fields['URLC'] : null).
            (isset($fields['firstname']) ? $fields['firstname'] : null).
            (isset($fields['lastname']) ? $fields['lastname'] : null).
            (isset($fields['email']) ? $fields['email'] : null).
            (isset($fields['street']) ? $fields['street'] : null).
            (isset($fields['street_n1']) ? $fields['street_n1'] : null).
            (isset($fields['street_n2']) ? $fields['street_n2'] : null).
            (isset($fields['state']) ? $fields['state'] : null).
            (isset($fields['addr3']) ? $fields['addr3'] : null).
            (isset($fields['city']) ? $fields['city'] : null).
            (isset($fields['postcode']) ? $fields['postcode'] : null).
            (isset($fields['phone']) ? $fields['phone'] : null).
            (isset($fields['country']) ? $fields['country'] : null).
            (isset($fields['code']) ? $fields['code'] : null).
            (isset($fields['p_info']) ? $fields['p_info'] : null).
            (isset($fields['p_email']) ? $fields['p_email'] : null).
            (isset($fields['n_email']) ? $fields['n_email'] : null).
            (isset($fields['expiration_date']) ? $fields['expiration_date'] : null).
            (isset($fields['deladdr']) ? $fields['deladdr'] : null).
            (isset($fields['recipient_account_number']) ? $fields['recipient_account_number'] : null).
            (isset($fields['recipient_company']) ? $fields['recipient_company'] : null).
            (isset($fields['recipient_first_name']) ? $fields['recipient_first_name'] : null).
            (isset($fields['recipient_last_name']) ? $fields['recipient_last_name'] : null).
            (isset($fields['recipient_address_street']) ? $fields['recipient_address_street'] : null).
            (isset($fields['recipient_address_building']) ? $fields['recipient_address_building'] : null).
            (isset($fields['recipient_address_apartment']) ? $fields['recipient_address_apartment'] : null).
            (isset($fields['recipient_address_postcode']) ? $fields['recipient_address_postcode'] : null).
            (isset($fields['recipient_address_city']) ? $fields['recipient_address_city'] : null).
            (isset($fields['application']) ? $fields['application'] : null).
            (isset($fields['application_version']) ? $fields['application_version'] : null).
            (isset($fields['warranty']) ? $fields['warranty'] : null).
            (isset($fields['bylaw']) ? $fields['bylaw'] : null).
            (isset($fields['personal_data']) ? $fields['personal_data'] : null).
            (isset($fields['credit_card_number']) ? $fields['credit_card_number'] : null).
            (isset($fields['credit_card_expiration_date_year']) ? $fields['credit_card_expiration_date_year'] : null).
            (isset($fields['credit_card_expiration_date_month']) ? $fields['credit_card_expiration_date_month'] : null).
            (isset($fields['credit_card_security_code']) ? $fields['credit_card_security_code'] : null).
            (isset($fields['credit_card_store']) ? $fields['credit_card_store'] : null).
            (isset($fields['credit_card_store_security_code']) ? $fields['credit_card_store_security_code'] : null).
            (isset($fields['credit_card_customer_id']) ? $fields['credit_card_customer_id'] : null).
            (isset($fields['credit_card_id']) ? $fields['credit_card_id'] : null).
            (isset($fields['blik_code']) ? $fields['blik_code'] : null).
            (isset($fields['credit_card_registration']) ? $fields['credit_card_registration'] : null).
            (isset($fields['recurring_frequency']) ? $fields['recurring_frequency'] : null).
            (isset($fields['recurring_interval']) ? $fields['recurring_interval'] : null).
            (isset($fields['recurring_start']) ? $fields['recurring_start'] : null).
            (isset($fields['recurring_count']) ? $fields['recurring_count'] : null).
            (isset($fields['surcharge_amount']) ? $fields['surcharge_amount'] : null).
            (isset($fields['surcharge']) ? $fields['surcharge'] : null).
            (isset($fields['ignore_last_payment_channel']) ? $fields['ignore_last_payment_channel'] : null);

        $fields['chk'] = hash('sha256', $string);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof DoPayment &&
            $request->getModel() instanceof \ArrayAccess;
    }
}
