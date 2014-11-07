<?php

namespace Barbondev\Payment\PayPointHostedBundle\Transaction;

/**
 * Interface ResponseHashValidatorInterface
 *
 * @package Barbondev\Payment\PayPointHostedBundle\Transaction
 * @author Ashley Dawson <ashley.dawson@barbon.com>
 */
interface ResponseHashValidatorInterface
{
    /**
     * Validates the PayPoint response query string and
     * returns TRUE if the response is valid
     *
     * @param string $requestUri
     * @param string $remotePassword PayPoint API remote password
     * @param string $hash
     * @return bool
     */
    public function validate($requestUri, $remotePassword, $hash);
}