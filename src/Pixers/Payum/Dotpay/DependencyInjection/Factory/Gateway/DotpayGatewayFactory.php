<?php

namespace Pixers\Payum\Dotpay\DependencyInjection\Factory\Gateway;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Payum\Bundle\PayumBundle\DependencyInjection\Factory\Gateway\AbstractGatewayFactory;
use Pixers\Payum\Dotpay\Constants;
use Pixers\Payum\Dotpay\Api;

/**
 * DotpayGatewayFactory
 *
 * @author Michał Kanak <kanakmichal@gmail.com>
 */
class DotpayGatewayFactory extends AbstractGatewayFactory
{

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'dotpay';
    }

    /**
     * {@inheritdoc}
     */
    public function addConfiguration(ArrayNodeDefinition $builder)
    {
        parent::addConfiguration($builder);

        $builder->children()
                ->scalarNode('id')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('URLC')->end()
                ->scalarNode('url')->end()
                ->enumNode('type')
                ->defaultValue(Constants::TYPE_RETURN_BUTTON_AND_NOTIFY)
                ->values([
                    Constants::TYPE_ONLY_RETURN_BUTTON,
                    Constants::TYPE_INFINITY_POST_NOTIFY,
                    Constants::TYPE_NO_INTERACTION,
                    Constants::TYPE_RETURN_BUTTON_AND_NOTIFY
                ])
                ->end()
                ->scalarNode('URLC')->end()
                ->scalarNode('PIN')->end()
                ->scalarNode('ip')->end()
                ->scalarNode('endpoint')->defaultValue(Api::DEFAULT_ENDPOINT)->end()
                ->scalarNode('method')
                ->defaultValue('GET')
                ->validate()
                ->ifNotInArray(['GET', 'POST'])
                ->thenInvalid('Invalid redirect method "%s"')
                ->end()
                ->end();
    }

    /**
     * {@inheritDoc}
     */
    protected function getPayumGatewayFactoryClass()
    {
        return 'Pixers\Payum\Dotpay\DotpayGatewayFactory';
    }

    /**
     * {@inheritDoc}
     */
    protected function getComposerPackage()
    {
        return 'pixers/payum-dotpay';
    }

}
