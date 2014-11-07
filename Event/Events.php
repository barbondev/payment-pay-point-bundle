<?php

namespace Barbondev\Payment\PayPointHostedBundle\Event;

/**
 * Class Events
 *
 * @package Barbondev\Payment\PayPointHostedBundle\Event
 * @author Ashley Dawson <ashley.dawson@barbon.com>
 */
final class Events
{
    /**
     * On gateway callback event (dispatched when the gateway response is first received)
     * Use with: GatewayCallbackEvent
     */
    const ON_GATEWAY_CALLBACK = 'barbondev.payment.paypoint_hosted.on_gateway_callback';

    /**
     * Private constructor as this is a static namespace
     */
    private function __construct()
    {
    }
}