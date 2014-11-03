<?php

namespace Barbondev\Payment\PayPointHostedBundle\Tests\Plugin;

use Barbondev\Payment\PayPointHostedBundle\Plugin\PayPointHostedPlugin;

/**
 * Class PayPointHostedPluginTest
 *
 * @package Barbondev\Payment\PayPointHostedBundle\Tests\Plugin
 * @author Ashley Dawson <ashley.dawson@barbon.com>
 */
class PayPointHostedPluginTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PayPointHostedPlugin
     */
    private $plugin;

    protected function setUp()
    {
        $this->plugin = new PayPointHostedPlugin();
    }

    public function testProcesses()
    {
        $this->assertTrue($this->plugin->processes('paypoint_hosted'));
    }
}