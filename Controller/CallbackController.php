<?php

namespace Barbondev\Payment\PayPointHostedBundle\Controller;

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
            // todo: throw exception
        }

        if ( ! $this->responseHashValidator->validate($request->getRequestUri(), $this->remotePassword, $request->get('hash'))) {
            if ($this->logger) {
                $this->logger->error('Response hash did not match computed hash');
            }
            // todo: throw exception
        }

        // todo: fire an event to notify parent app

        // ...

        /*
        $this->request->query->all()
        Array
        (
            [valid] => true
            [trans_id] => 56
            [code] => A
            [auth_code] => 9999
            [expiry] => 0915
            [card_no] => 1111
            [customer] => MR T TEST
            [amount] => 24.28
            [ip] => 195.59.185.214
            [test_status] => true
            [mpi_status_code] => 210
            [mpi_message] => Other Result
            [hash] => d422b47f7cfcc74eb837b212beb5ec0e
        )
        */

        return new Response('<pre>'.print_r($request->query->all(), true).'</pre>');
    }
}