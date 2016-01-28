# Payum Dotpay by Pixers

Integrate Payum with Dotpay api

# Get it started.

Library offers additional payum gateway for dotpay service.

## Installation

The preferred way to install the library is using [composer](http://getcomposer.org/).
Run:

```bash
php composer.phar require "pixers/payum-dotpay"
```

## Configuration

Symfony2 - how to add officialy unsupported DotpayGateway:

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

When we like to set param URLC - URLCallback, we must additionally in dotpay panel check option - Ustawienia -> parametry URLC - turn on.
More information: http://dotpay.pl/files/dotpay_instrukcja_techniczna.pdf page 25.

in config.yml:

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

## Resources

* [Payum Documentation](http://payum.org/doc)
* [Dotpay Documentation](http://dotpay.pl/files/dotpay_instrukcja_techniczna.pdf)

## Author

* Michał Kanak <michal.kanak@pixers.pl>

## License

Copyright 2016 PIXERS Ltd - www.pixersize.com

Licensed under the [BSD 3-Clause](LICENSE)