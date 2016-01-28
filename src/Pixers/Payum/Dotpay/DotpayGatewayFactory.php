<?php

namespace Pixers\Payum\Dotpay;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;
use Pixers\Payum\Dotpay\Action\CaptureAction;
use Pixers\Payum\Dotpay\Action\StatusAction;
use Pixers\Payum\Dotpay\Action\ConvertPaymentAction;
use Pixers\Payum\Dotpay\Action\DoPaymentAction;
use Pixers\Payum\Dotpay\Action\NotifyAction;
use Pixers\Payum\Dotpay\Action\SyncAction;
use Pixers\Payum\Dotpay\Api;

/**
 * 
 * @author Michał Kanak <kanakmichal@gmail.com>
 */
class DotpayGatewayFactory extends GatewayFactory
{

    /**
     * 
     * @param ArrayObject $config
     */
    protected function populateConfig(ArrayObject $config)
    {
        $config->defaults([
            'payum.factory_name' => 'dotpay',
            'payum.factory_title' => 'Dotpay',
            'payum.action.capture' => new CaptureAction(),
            'payum.action.status' => new StatusAction(),
            'payum.action.notify' => new NotifyAction(),
            'payum.action.sync' => new SyncAction($config),
            'payum.action.convert_payment' => new ConvertPaymentAction(),
            'payum.action.api.do_payment' => new DoPaymentAction(),
        ]);

        if (false == $config['payum.api']) {
            $config['payum.default_options'] = [
                'id' => '',
                'URLC' => '',
                'endpoint' => Api::DEFAULT_ENDPOINT,
                'method' => 'GET',
            ];
            $config->defaults($config['payum.default_options']);
            $config['payum.required_options'] = ['id'];

            $config['payum.api'] = function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);

                $dotpayConfig = [
                    'id' => $config['id'],
                    'URLC' => $config['URLC'],
                    'endpoint' => $config['endpoint'],
                    'method' => $config['method'],
                    'url' => $config['url'],
                    'type' => $config['type'],
                    'PIN' => $config['PIN'],
                    'ip' => $config['ip']
                ];

                return new Api($dotpayConfig, $config['payum.http_client']);
            };
        }
    }

}
