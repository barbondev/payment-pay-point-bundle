<?php

namespace Barbondev\Payment\PayPointHostedBundle\Digestor;

/**
 * Interface DigestorInterface
 *
 * @package Barbondev\Payment\PayPointHostedBundle\Digestor
 * @author Ashley Dawson <ashley.dawson@barbon.com>
 */
interface DigestorInterface
{
    /**
     * Digest credentials and parameters and produce a digest to
     * validate the initial PayPoint request
     *
     * @param string $transactionId
     * @param float $amount
     * @param string $remotePassword
     * @return string
     */
    public function digest($transactionId, $amount, $remotePassword);
}