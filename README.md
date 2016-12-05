# Payum Dotpay by Pixers

Integrate Payum with Dotpay API.

# Get it started.

Library offers additional Payum gateway for Dotpay (www.dotpay.pl) service.

## Installation

The preferred way to install the library is using [composer](http://getcomposer.org/).

Run:

```bash
php composer.phar require "pixers/payum-dotpay"
```

## Symfony Integration

### Symfony2 - how to add DotpayGateway v1.0.2 for (1.x > payum-bundle < 2.0):

```php
<?php

namespace Acme\PaymentBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Payum\Bundle\PayumBundle\DependencyInjection\Compiler\BuildRegistryPass;
use Payum\Bundle\PayumBundle\DependencyInjection\Compiler\BuildGatewayFactoryPass;
use Pixers\Payum\Dotpay\DependencyInjection\Factory\Gateway\DotpayGatewayFactory;

class AcmePaymentBundle extends Bundle
{

    /**
     * Add Dotpay Gateway to PaymentBundle
     *
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        /** @var $extension PayumExtension */
        $extension = $container->getExtension('payum');

        $extension->addGatewayFactory(new DotpayGatewayFactory());

        $container->addCompilerPass(new BuildRegistryPass());
        $container->addCompilerPass(new BuildGatewayFactoryPass());
    }

}
```

If we'd like to set URLC parameter (URL callback), we additionally have to turn on option "Ustawienia -> parametry URLC" in Dotpay panel.
More information: http://dotpay.pl/files/dotpay_instrukcja_techniczna.pdf (page 25).

Config.yml:

```php
payum:
    gateways:
        dotpay_checkout:
            dotpay:
                id: company_id_from_dotpay_panel
                method: 'POST'|'GET'                // (optional) default=GET
                URLC: '//some_url',                 // (optional)
                url: '//some_url',                  // (optional)
                endpoint: 'https://ssl.dotpay.pl/', //(optional)
                type: 0|1|2|3,                      // (optional) default=3 
                PIN: hjkert543dgt67yh,              // for URLC callbacks (must be set before in dotpay panel) (optional)
                ip: 195.150.9.37                    // this dotpay ip calls to URLC (optional)
```

### Symfony2 - how to add DotpayGateway v2.0 for payum-bundle >= 2.0:

services.yml

```php
    dotpay_checkout:
        class: Payum\Core\Bridge\Symfony\Builder\GatewayFactoryBuilder
        arguments: [Pixers\Payum\Dotpay\DotpayGatewayFactory]
        tags:
            - { name: payum.gateway_factory_builder, factory: dotpay }
```

Config.yml:

```php
payum:
    gateways:
        dotpay_checkout:
            factory: dotpay
            id: company_id_from_dotpay_panel
            method: 'POST'|'GET'                // (optional) default=GET
            URLC: '//some_url',                 // (optional)
            url: '//some_url',                  // (optional)
            endpoint: 'https://ssl.dotpay.pl/', // (optional)
            type: 0|1|2|3,                      // (optional) default=3 
            PIN: hjkert543dgt67yh,              // for URLC callbacks (must be set before in dotpay panel) (optional)
            ip: 195.150.9.37                    // this dotpay ip calls to URLC (optional)
```

## Resources

* [Payum Repository](https://github.com/Payum/Payum)
* [Dotpay Documentation](http://dotpay.pl/files/dotpay_instrukcja_techniczna.pdf)

## Author

* Michał Kanak <michal.kanak@pixers.pl>

## License

Copyright 2016 PIXERS Ltd - www.pixersize.com

Licensed under the [BSD 3-Clause](LICENSE)
