<?php

namespace Pixers\Payum\Dotpay\Action\Api;

use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Exception\UnsupportedApiException;
use Pixers\Payum\Dotpay\Api;

abstract class BaseApiAwareAction implements ActionInterface, ApiAwareInterface
{

    /**
     * @var \Dotpay\Payum\Api
     */
    protected $api;

    /**
     * {@inheritDoc}
     */
    public function setApi($api)
    {
        if (false == $api instanceof Api) {
            throw new UnsupportedApiException('Not supported.');
        }

        $this->api = $api;
    }

}
