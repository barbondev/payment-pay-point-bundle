<?php

namespace Barbondev\Payment\PayPointHostedBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use JMS\Payment\CoreBundle\Entity\FinancialTransaction;

/**
 * Class GatewayCallbackEvent
 *
 * @package Barbondev\Payment\PayPointHostedBundle\Event
 * @author Ashley Dawson <ashley.dawson@barbon.com>
 */
class GatewayCallbackEvent extends Event
{
    /**
     * @var FinancialTransaction
     */
    private $transaction;

    /**
     * @var array
     */
    private $gatewayResponseParams;

    /**
     * Constructor
     *
     * @param FinancialTransaction $transaction
     * @param array $gatewayResponseParams
     */
    public function __construct(FinancialTransaction $transaction, array $gatewayResponseParams)
    {
        $this->transaction = $transaction;
        $this->gatewayResponseParams = $gatewayResponseParams;
    }

    /**
     * Get gatewayResponseParams
     *
     * @return array
     */
    public function getGatewayResponseParams()
    {
        return $this->gatewayResponseParams;
    }

    /**
     * Get transaction
     *
     * @return FinancialTransaction
     */
    public function getTransaction()
    {
        return $this->transaction;
    }
}