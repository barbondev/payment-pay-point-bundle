<?php

namespace Barbondev\Payment\PayPointHostedBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @package Barbondev\Payment\PayPointHostedBundle\DependencyInjection
 * @author Ashley Dawson <ashley.dawson@barbon.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $treeBuilder
            ->root('barbon_payment_paypoint_hosted', 'array')
                ->children()
                    ->scalarNode('merchant')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('remote_password')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('gateway_url')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('test_status')->isRequired()->cannotBeEmpty()->end()
                    ->scalarNode('repeat')->defaultNull()->end()
                    ->scalarNode('test_mpi_status')->defaultNull()->end()
                    ->scalarNode('usage_type')->defaultNull()->end()
                    ->scalarNode('dups')->defaultNull()->end()
                    ->scalarNode('template')->defaultNull()->end()
                ->end()
            ->end()
        ;

        return $treeBuilder->buildTree();
    }
}
