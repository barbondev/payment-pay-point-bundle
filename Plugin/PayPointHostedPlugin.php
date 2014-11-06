<?php

namespace Barbondev\Payment\PayPointHostedBundle\Plugin;

use JMS\Payment\CoreBundle\Model\FinancialTransactionInterface;
use JMS\Payment\CoreBundle\Plugin\AbstractPlugin;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

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
     * @var string
     */
    private $merchant;

    /**
     * @var string
     */
    private $gatewayUrl;

    /**
     * Constructor
     *
     * @param EventDispatcherInterface $eventDispatcher
     * @param EngineInterface $templating
     * @param string $merchant
     * @param string $gatewayUrl
     * @param bool $isDebug
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        EngineInterface $templating,
        $merchant,
        $gatewayUrl,
        $isDebug = false)
    {
        parent::__construct($isDebug);
        $this->templating = $templating;
        $this->eventDispatcher = $eventDispatcher;
        $this->merchant = $merchant;
        $this->gatewayUrl = $gatewayUrl;
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
        $that = $this;

        $this->eventDispatcher->addListener(
            KernelEvents::RESPONSE,
            function (FilterResponseEvent $event) use ($that, $transaction) {
                $event->setResponse(
                    $that->templating->renderResponse(
                        '@BarbondevPaymentPayPointHosted/PayPointHostedPlugin/payment.html.twig',
                        array(
                            'merchant' => $that->merchant,
                            'transactionId' => $transaction->getId(),
                            'amount' => $transaction->getRequestedAmount(),
                            'callback' => 'callb', // todo: add this
                            'digest' => 'diges', // todo: add this
                            'gatewayUrl' => $that->gatewayUrl,
                        )
                    )
                );
            }
        );
    }
}