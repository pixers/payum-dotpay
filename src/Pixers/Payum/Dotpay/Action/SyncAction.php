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
 * SyncAction
 *
 * @author Michał Kanak <kanakmichal@gmail.com>
 */
class SyncAction extends GatewayAwareAction implements ActionInterface
{

    /**
     *
     * @var ArrayObject Api config 
     */
    protected $config;

    /**
     * 
     * @param ArrayObject $config
     */
    public function __construct(ArrayObject $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritDoc}
     */
    public function execute($request)
    {
        /** @var $request Sync */
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $this->gateway->execute($httpRequest = new GetHttpRequest());
        $requestData = $httpRequest->request;

        $this->assertRequestValid($httpRequest);
        
        if ($requestData['status'] != 'OK') {
            $model[Constants::FIELD_STATUS] = Constants::STATUS_FAILED;

            return;
        }

        if ($requestData['t_status'] == Constants::DOTPAY_STATUS_NEW) {
            $model[Constants::FIELD_STATUS] = Constants::STATUS_NEW;
        }

        if ($requestData['t_status'] == Constants::DOTPAY_STATUS_SUCCESS) {
            $model[Constants::FIELD_STATUS] = Constants::STATUS_AUTHORIZED;
        }

        if ($requestData['t_status'] == Constants::DOTPAY_STATUS_ERROR) {
            $model[Constants::FIELD_STATUS] = Constants::STATUS_FAILED;
        }

        if ($requestData['t_status'] == Constants::DOTPAY_STATUS_CANCEL) {
            $model[Constants::FIELD_STATUS] = Constants::STATUS_CANCELED;
        }

        if ($requestData['t_status'] == Constants::DOTPAY_STATUS_COMPLAINT) {
            $model[Constants::FIELD_STATUS] = Constants::STATUS_COMPLAINT;
        }

        if (isset($requestData['t_id'])) {
            $model['t_id'] = $requestData['t_id'];
        }

        if (isset($requestData['channel'])) {
            $model['channel'] = $requestData['channel'];
        }

        if (isset($requestData['amount'])) {
            $model['amount'] = $requestData['amount'];
        }
    }

    /**
     * {@inheritDoc}
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
     * 
     * @param array $requestData
     * @return string md5
     */
    protected function generateDotpaySecureHash(array $requestData = [])
    {
        $PIN = (isset($this->config['PIN'])) ? $this->config['PIN'] : '';
        $id = $this->config['id'];
        $control = (isset($requestData['control'])) ? $requestData['control'] : '';
        $t_id = (isset($requestData['t_id'])) ? $requestData['t_id'] : '';
        $amount = (isset($requestData['amount'])) ? $requestData['amount'] : '';
        $email = (isset($requestData['email'])) ? $requestData['email'] : '';
        $service = (isset($requestData['service'])) ? $requestData['service'] : '';
        $code = (isset($requestData['code'])) ? $requestData['code'] : '';
        $username = (isset($requestData['username'])) ? $requestData['username'] : '';
        $password = (isset($requestData['password'])) ? $requestData['password'] : '';
        $t_status = (isset($requestData['t_status'])) ? $requestData['t_status'] : '';

        return md5("$PIN:$id:$control:$t_id:$amount:$email:$service:$code:$username:$password:$t_status");
    }
    
    /**
     * @param array $httpRequest
     * @throws InvalidArgumentException
     */
    protected function assertRequestValid($httpRequest)
    {
        $requestData = $httpRequest->request;

        // Validation
        $md5 = $this->generateDotpaySecureHash($requestData);
        if (!isset($requestData['md5'])) {
            throw new InvalidArgumentException("Md5 is not set", 400);
        }
        if ($md5 != $requestData['md5']) {
            throw new InvalidArgumentException("Md5 is not valid", 400);
        }
        if (empty($this->config['ip'])) {
            return true;
        }
        if ($this->config['ip'] != $httpRequest->clientIp) {
            throw new InvalidArgumentException("Ip does not match", 400);
        }
    }
}
