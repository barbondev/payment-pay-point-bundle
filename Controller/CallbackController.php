<?php

namespace Barbondev\Payment\PayPointHostedBundle\Controller;

use JMS\Payment\CoreBundle\Model\PaymentInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Persistence\ObjectManager;
use Barbondev\Payment\PayPointHostedBundle\Transaction\ResponseHashValidatorInterface;
use JMS\Payment\CoreBundle\PluginController\EntityPluginController;
use Psr\Log\LoggerInterface;

/**
 * Class CallbackController
 *
 * @package Barbondev\Payment\PayPointHostedBundle\Controller
 * @author Ashley Dawson <ashley.dawson@barbon.com>
 */
class CallbackController
{
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ResponseHashValidatorInterface
     */
    private $responseHashValidator;

    /**
     * @var EntityPluginController
     */
    private $paymentPluginController;

    /**
     * @var string
     */
    private $remotePassword;

    /**
     * Constructor
     *
     * @param ObjectManager $em
     * @param \Barbondev\Payment\PayPointHostedBundle\Transaction\ResponseHashValidatorInterface $responseHashValidator
     * @param \JMS\Payment\CoreBundle\PluginController\EntityPluginController $paymentPluginController
     * @param string $remotePassword
     */
    public function __construct(
        ObjectManager $em,
        ResponseHashValidatorInterface $responseHashValidator,
        EntityPluginController $paymentPluginController,
        $remotePassword)
    {
        $this->em = $em;
        $this->responseHashValidator = $responseHashValidator;
        $this->remotePassword = $remotePassword;
        $this->paymentPluginController = $paymentPluginController;
    }

    /**
     * Set logger
     *
     * @param LoggerInterface $logger
     * @return $this
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * PayPoint callback action
     *
     * @param Request $request
     * @return Response
     */
    public function gatewayCallbackAction(Request $request)
    {
        $params = $request->query;

        if ( ! $params->has('trans_id')) {
            if ($this->logger) {
                $this->logger->error('The trans_id could not be found in callback');
            }
            return new Response('FAIL', 500);
        }

        if ( ! $this->responseHashValidator->validate($request->getRequestUri(), $this->remotePassword, $request->get('hash'))) {
            if ($this->logger) {
                $this->logger->error('Response hash did not match computed hash');
            }
            return new Response('FAIL', 500);
        }

        $transaction = $this->em->getRepository('JMSPaymentCoreBundle:FinancialTransaction')->findOneBy(array(
            'referenceNumber' => $params->get('trans_id'),
        ));

        if ( ! $transaction) {
            if ($this->logger) {
                $this->logger->error('Transaction could not be found for {transactionId}', array(
                    'transactionId' => $params->get('trans_id'),
                ));
            }
            return new Response('FAIL', 500);
        }

        $amount = $request->query->get('amount');

        // todo: fire an event to notify parent app

        /** @var \JMS\Payment\CoreBundle\Entity\Payment $payment */
        $payment = $transaction->getPayment();

        foreach ($request->query->all() as $param => $value) {
            $transaction->getExtendedData()->set($param, $value);
        }

        $payment->setState(PaymentInterface::STATE_DEPOSITING);
        $payment->setDepositingAmount($amount);

        $this->em->persist($transaction);
        $this->em->persist($payment);
        $this->em->flush();

        $result = $this->paymentPluginController->deposit(
            $transaction->getPayment()->getId(),
            $transaction->getRequestedAmount()
        );

        return new Response('OK', 200);
    }
}