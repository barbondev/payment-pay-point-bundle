<?php

namespace Barbondev\Payment\PayPointHostedBundle\Plugin;

use Barbondev\Payment\PayPointHostedBundle\Digestor\DigestorInterface;
use Barbondev\Payment\PayPointHostedBundle\Exception\PayPointCallbackNotProvidedException;
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
    private $remotePassword;

    /**
     * @var string
     */
    private $gatewayUrl;

    /**
     * @var DigestorInterface
     */
    private $digestor;

    /**
     * Constructor
     *
     * @param EventDispatcherInterface $eventDispatcher
     * @param EngineInterface $templating
     * @param DigestorInterface $digestor
     * @param string $merchant
     * @param string $remotePassword
     * @param string $gatewayUrl
     * @param bool $isDebug
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        EngineInterface $templating,
        DigestorInterface $digestor,
        $merchant,
        $remotePassword,
        $gatewayUrl,
        $isDebug = false)
    {
        parent::__construct($isDebug);
        $this->templating = $templating;
        $this->eventDispatcher = $eventDispatcher;
        $this->merchant = $merchant;
        $this->remotePassword = $remotePassword;
        $this->gatewayUrl = $gatewayUrl;
        $this->digestor = $digestor;
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
        $transactionId = $transaction->getId();
        $amount = $transaction->getRequestedAmount();

        $digest = $this->digestor->digest($transactionId, $amount, $this->remotePassword);
        $data = $transaction->getExtendedData();

        if ( ! $data->has('callback')) {
            throw new PayPointCallbackNotProvidedException(
                'Callback URL not provided - please add to "predefined_data" when setting up payment selection form');
        }

        $that = $this;

        $this->eventDispatcher->addListener(
            KernelEvents::RESPONSE,
            function (FilterResponseEvent $event) use ($that, $transactionId, $amount, $digest, $data) {
                $event->setResponse(
                    $that->templating->renderResponse(
                        '@BarbondevPaymentPayPointHosted/PayPointHostedPlugin/payment.html.twig',
                        array(
                            'merchant' => $that->merchant,
                            'transactionId' => $transactionId,
                            'amount' => $amount,
                            'callback' => $data->get('callback'),
                            'digest' => $digest,
                            'gatewayUrl' => $that->gatewayUrl,
                        )
                    )
                );
            }
        );
    }
}