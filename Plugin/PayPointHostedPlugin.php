<?php

namespace Barbondev\Payment\PayPointHostedBundle\Plugin;

use Barbondev\Payment\PayPointHostedBundle\Digestor\DigestorInterface;
use Barbondev\Payment\PayPointHostedBundle\Exception\PayPointCallbackNotProvidedException;
use Barbondev\Payment\PayPointHostedBundle\Transaction\ReferenceGeneratorInterface;
use Barbondev\Payment\PayPointHostedBundle\Transaction\ResponseHashValidatorInterface;
use JMS\Payment\CoreBundle\Model\FinancialTransactionInterface;
use JMS\Payment\CoreBundle\Model\PaymentInterface;
use JMS\Payment\CoreBundle\Plugin\AbstractPlugin;
use JMS\Payment\CoreBundle\Plugin\PluginInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\Routing\RouterInterface;

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
     * @var DigestorInterface
     */
    private $digestor;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var ReferenceGeneratorInterface
     */
    private $transactionReferenceGenerator;

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
     * @var string
     */
    private $testStatus;

    /**
     * @var string
     */
    private $repeat;

    /**
     * @var string
     */
    private $testMpiStatus;

    /**
     * @var string
     */
    private $usageType;

    /**
     * @var string
     */
    private $dups;

    /**
     * @var string
     */
    private $template;

    /**
     * Constructor
     *
     * @param EventDispatcherInterface $eventDispatcher
     * @param EngineInterface $templating
     * @param DigestorInterface $digestor
     * @param RouterInterface $router
     * @param ReferenceGeneratorInterface $transactionReferenceGenerator
     * @param string $merchant
     * @param string $remotePassword
     * @param string $gatewayUrl
     * @param string $testStatus
     * @param string $repeat
     * @param string $testMpiStatus
     * @param string $usageType
     * @param string $dups
     * @param string $template
     * @param bool $isDebug
     *
     * @todo: replace these params with a configuration object
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        EngineInterface $templating,
        DigestorInterface $digestor,
        RouterInterface $router,
        ReferenceGeneratorInterface $transactionReferenceGenerator,
        $merchant,
        $remotePassword,
        $gatewayUrl,
        $testStatus,
        $repeat,
        $testMpiStatus,
        $usageType,
        $dups,
        $template,
        $isDebug = false)
    {
        parent::__construct($isDebug);
        $this->templating = $templating;
        $this->eventDispatcher = $eventDispatcher;
        $this->merchant = $merchant;
        $this->remotePassword = $remotePassword;
        $this->gatewayUrl = $gatewayUrl;
        $this->digestor = $digestor;
        $this->testStatus = $testStatus;
        $this->repeat = $repeat;
        $this->testMpiStatus = $testMpiStatus;
        $this->usageType = $usageType;
        $this->dups = $dups;
        $this->template = $template;
        $this->router = $router;
        $this->transactionReferenceGenerator = $transactionReferenceGenerator;
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
    public function approve(FinancialTransactionInterface $transaction, $retry)
    {
        $transactionReference = $this->transactionReferenceGenerator->generate();
        $amount = number_format($transaction->getRequestedAmount(), 2, '.', '');

        $digest = $this->digestor->digest($transactionReference, $amount, $this->remotePassword);
        $callbackUrl = $this->router->generate('barbondev_payment_paypoint_hosted_gateway_callback', array(), true);

        $that = $this;

        $this->eventDispatcher->addListener(
            KernelEvents::RESPONSE,
            function (FilterResponseEvent $event) use ($that, $transactionReference, $amount, $digest, $callbackUrl) {
                $event->setResponse(
                    $that->getTemplating()->renderResponse(
                        '@BarbondevPaymentPayPointHosted/PayPointHostedPlugin/payment.html.twig', // todo: make this configurable (or overridable)
                        array(
                            'merchant' => $that->getMerchant(),
                            'transactionId' => $transactionReference,
                            'amount' => $amount,
                            'callback' => $callbackUrl,
                            'digest' => $digest,
                            'gatewayUrl' => $that->getGatewayUrl(),
                            'testStatus' => $that->getTestStatus(),
                            'optionalParams' => array(
                                'repeat' => $that->getRepeat(),
                                'test_mpi_status' => $that->getTestMpiStatus(),
                                'usage_type' => $that->getUsageType(),
                                'dups' => $that->getDups(),
                                'template' => $that->getTemplate(),
                            ),
                        )
                    )
                );
            }
        );

        $transaction->setReferenceNumber($transactionReference);
        $transaction->setResponseCode(PluginInterface::RESPONSE_CODE_PENDING);
        $transaction->setReasonCode(PluginInterface::REASON_CODE_ACTION_REQUIRED);
        $transaction->getPayment()->setApprovedAmount($amount);
    }

    /**
     * {@inheritdoc}
     */
    public function deposit(FinancialTransactionInterface $transaction, $retry)
    {
        $data = $transaction->getExtendedData();

        if ('false' === $data->get('valid')) {
            $transaction->setResponseCode('failed');
            $transaction->setReasonCode(PluginInterface::REASON_CODE_INVALID);
            return;
        }

        switch ($data->get('code')) {

            case PayPointResponseCodes::AUTHORISED:
                $transaction->setReferenceNumber($data->get('trans_id'));
                $transaction->setProcessedAmount($data->get('amount'));
                $transaction->setResponseCode(PluginInterface::RESPONSE_CODE_SUCCESS);
                $transaction->setReasonCode(PluginInterface::REASON_CODE_SUCCESS);
                $transaction->setState(PaymentInterface::STATE_DEPOSITED);
                return;
        }

        $transaction->setResponseCode('unknown');
        $transaction->setState(PaymentInterface::STATE_FAILED);
        $transaction->setReasonCode(PluginInterface::REASON_CODE_INVALID);
    }
    
    /**
     * Get templating
     * 
     * @return EngineInterface
     */
    public function getTemplating()
    {
        return $this->templating;
    }
    
    /**
     * Get merchant
     * 
     * @return string
     */
    public function getMerchant()
    {
        return $this->merchant;
    }
    
    /**
     * Get gateway url
     * 
     * @return string
     */
    public function getGatewayUrl()
    {
        return $this->gatewayUrl;
    }
    
    /**
     * Get test status
     * 
     * @return string
     */
    public function getTestStatus()
    {
        return $this->testStatus;
    }
    
    /**
     * Get repeat
     * 
     * @return string
     */
    public function getRepeat()
    {
        return $this->repeat;
    }
    
    /**
     * Get test mpi status
     * 
     * @return string
     */
    public function getTestMpiStatus()
    {
        return $this->testMpiStatus;
    }
    
    /**
     * Get usage type
     * 
     * @return string
     */
    public function getUsageType()
    {
        return $this->usageType;
    }
    
    /**
     * Get dups
     * 
     * @return string
     */
    public function getDups()
    {
        return $this->dups;
    }
    
    /**
     * Get template
     * 
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }
}
