<?php

namespace Barbondev\Payment\PayPointHostedBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class BarbondevPaymentPayPointHostedExtension
 *
 * @package Barbondev\Payment\PayPointHostedBundle\DependencyInjection
 * @author Ashley Dawson <ashley.dawson@barbon.com>
 */
class BarbondevPaymentPayPointHostedExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        // ...
    }
}