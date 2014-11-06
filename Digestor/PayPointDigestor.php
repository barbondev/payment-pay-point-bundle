<?php

namespace Barbondev\Payment\PayPointHostedBundle\Digestor;

/**
 * Class PayPointDigestor
 *
 * @package Barbondev\Payment\PayPointHostedBundle\Digestor
 * @author Ashley Dawson <ashley.dawson@barbon.com>
 */
class PayPointDigestor implements DigestorInterface
{
    /**
     * {@inheritdoc}
     */
    public function digest($transactionId, $amount, $remotePassword)
    {
        // todo: does $amount need to be zero padded?
        return md5($transactionId . $amount . $remotePassword);
    }
}