<?php

namespace Barbondev\Payment\PayPointHostedBundle\Plugin;

use JMS\Payment\CoreBundle\Plugin\AbstractPlugin;

/**
 * Class PayPointHostedPlugin
 *
 * @package Barbondev\Payment\PayPointHostedBundle\Plugin
 * @author Ashley Dawson <ashley.dawson@barbon.com>
 */
class PayPointHostedPlugin extends AbstractPlugin
{
    /**
     * {@inheritdoc}
     */
    function processes($paymentSystemName)
    {
        return ('paypoint_hosted' == $paymentSystemName);
    }
}