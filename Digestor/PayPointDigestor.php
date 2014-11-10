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
        // todo: check to see if decimal format is always required for amount, e.g. 4.7 becomes 4.70
        return md5($transactionId . $amount . $remotePassword);
    }
}