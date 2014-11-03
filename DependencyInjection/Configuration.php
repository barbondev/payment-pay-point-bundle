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

        // ...

        return $treeBuilder;
    }
}