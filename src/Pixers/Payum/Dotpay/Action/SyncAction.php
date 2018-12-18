<?php

namespace Pixers\Payum\Dotpay\Action;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Action\GatewayAwareAction;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\GetHttpRequest;
use Payum\Core\Exception\InvalidArgumentException;
use Pixers\Payum\Dotpay\Constants;
use Pixers\Payum\Dotpay\Request\Api\Sync;

/**
 * SyncAction.
 *
 * @author Michał Kanak <kanakmichal@gmail.com>
 */
class SyncAction extends GatewayAwareAction implements ActionInterface
{
    /**
     * @var ArrayObject Api config
     */
    protected $config;

    /**
     * @param ArrayObject $config
     */
    public function __construct(ArrayObject $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function execute($request)
    {
        /* @var $request Sync */
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $this->gateway->execute($httpRequest = new GetHttpRequest());
        $requestData = $httpRequest->request;
        $this->assertRequestValid($httpRequest->clientIp, $requestData);

        if (Constants::DOTPAY_STATUS_NEW == $requestData['operation_status']) {
            $model[Constants::FIELD_STATUS] = Constants::STATUS_NEW;
        }

        if (Constants::DOTPAY_STATUS_COMPLETED == $requestData['operation_status']) {
            $model[Constants::FIELD_STATUS] = Constants::STATUS_CAPTURED;
        }

        if (Constants::DOTPAY_STATUS_REJECTED == $requestData['operation_status']) {
            $model[Constants::FIELD_STATUS] = Constants::STATUS_FAILED;
        }

        if (Constants::DOTPAY_STATUS_PROCESSING == $requestData['operation_status']) {
            $model[Constants::FIELD_STATUS] = Constants::STATUS_PENDING;
        }

        if (isset($requestData['operation_number'])) {
            $model['operation_number'] = $requestData['operation_number'];
        }

        if (isset($requestData['channel'])) {
            $model['channel'] = $requestData['channel'];
        }

        if (isset($requestData['amount'])) {
            $model['amount'] = $requestData['amount'];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        if (false == $request instanceof Sync) {
            return false;
        }

        $model = $request->getModel();
        if (false == $model instanceof \ArrayAccess) {
            return false;
        }

        return true;
    }

    /**
     * @param array $requestData
     *
     * @return string
     */
    protected function generateDotpaySecureHash(array $requestData = [])
    {
        $pin = (isset($this->config['PIN'])) ? $this->config['PIN'] : '';
        $keys = [
            'id',
            'operation_number',
            'operation_type',
            'operation_status',
            'operation_amount',
            'operation_currency',
            'operation_withdrawal_amount',
            'operation_commission_amount',
            'is_completed',
            'operation_original_amount',
            'operation_original_currency',
            'operation_datetime',
            'operation_related_number',
            'control',
            'description',
            'email',
            'p_info',
            'p_email',
            'credit_card_issuer_identification_number',
            'credit_card_masked_number',
            'credit_card_brand_codename',
            'credit_card_brand_code',
            'credit_card_id',
            'channel',
            'channel_country',
            'geoip_country',
        ];

        $concatData = '';
        foreach ($keys as $key) {
            if (isset($requestData[$key]) && strlen($requestData[$key])) {
                $concatData .= $requestData[$key];
            }
        }

        return hash('sha256', $pin.$concatData);
    }

    /**
     * @param $clientIp
     * @param $requestData
     *
     * @return bool
     */
    protected function assertRequestValid($clientIp, $requestData)
    {
        $hash = $this->generateDotpaySecureHash($requestData);
        if (!isset($requestData['signature'])) {
            throw new InvalidArgumentException('signature is not set', 400);
        }
        if ($hash != $requestData['signature']) {
            throw new InvalidArgumentException('signature is not valid', 400);
        }
        if (empty($this->config['ip'])) {
            return true;
        }
        if ($this->config['ip'] != $clientIp) {
            throw new InvalidArgumentException('Ip does not match', 400);
        }
    }
}
