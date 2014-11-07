<?php

namespace Barbondev\Payment\PayPointHostedBundle\Transaction;

/**
 * Class ResponseHashValidator
 *
 * @package Barbondev\Payment\PayPointHostedBundle\Transaction
 * @author Ashley Dawson <ashley.dawson@barbon.com>
 */
class ResponseHashValidator implements ResponseHashValidatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function validate($requestUri, $remotePassword, $hash)
    {
        $requestUri = preg_replace('/\&hash=[a-f0-9]{32}/', '&', $requestUri);
        return (md5($requestUri . $remotePassword) === $hash);
    }
}