<?php

namespace Barbondev\Payment\PayPointHostedBundle\Plugin;

use JMS\Payment\CoreBundle\Model\FinancialTransactionInterface;
use JMS\Payment\CoreBundle\Plugin\AbstractPlugin;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class PayPointHostedPlugin
 *
 * @package Barbondev\Payment\PayPointHostedBundle\Plugin
 * @author Ashley Dawson <ashley.dawson@barbon.com>
 */
class PayPointHostedPlugin extends AbstractPlugin
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * Constructor
     *
     * @param EventDispatcherInterface $eventDispatcher
     * @param EngineInterface $templating
     * @param bool $isDebug
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, EngineInterface $templating, $isDebug = false)
    {
        parent::__construct($isDebug);
        $this->templating = $templating;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function processes($paymentSystemName)
    {
        return ('paypoint_hosted' === $paymentSystemName);
    }

    /**
     * {@inheritdoc}
     */
    public function approveAndDeposit(FinancialTransactionInterface $transaction, $retry)
    {
        // ...
    }
}