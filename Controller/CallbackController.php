<?php

namespace Barbondev\Payment\PayPointHostedBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Persistence\ObjectManager;
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
     * Constructor
     *
     * @param ObjectManager $em
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(ObjectManager $em, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    public function gatewayCallbackAction(Request $request)
    {
        $params = $request->query;

        if ( ! $params->has('trans_id')) {
            // todo: log and throw exception
        }

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