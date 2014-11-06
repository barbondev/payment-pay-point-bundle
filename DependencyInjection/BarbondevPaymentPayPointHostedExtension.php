<?php

namespace Barbondev\Payment\PayPointHostedBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
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
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $processor = new Processor();
        $config = $processor->process($configuration->getConfigTreeBuilder(), $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        // Mandatory
        $container->setParameter('barbon.payment.paypoint_hosted.merchant', $config['merchant']);
        $container->setParameter('barbon.payment.paypoint_hosted.remote_password', $config['remote_password']);
        $container->setParameter('barbon.payment.paypoint_hosted.gateway_url', $config['gateway_url']);
        $container->setParameter('barbon.payment.paypoint_hosted.test_status', $config['test_status']);

        // Optional
        $container->setParameter('barbon.payment.paypoint_hosted.repeat', $config['repeat']);
        $container->setParameter('barbon.payment.paypoint_hosted.test_mpi_status', $config['test_mpi_status']);
        $container->setParameter('barbon.payment.paypoint_hosted.usage_type', $config['usage_type']);
        $container->setParameter('barbon.payment.paypoint_hosted.dups', $config['dups']);
        $container->setParameter('barbon.payment.paypoint_hosted.template', $config['template']);
    }
}
