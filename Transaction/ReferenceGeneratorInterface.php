<?php

namespace Barbondev\Payment\PayPointHostedBundle\Transaction;

/**
 * Interface ReferenceGeneratorInterface
 *
 * @package Barbondev\Payment\PayPointHostedBundle\Transaction
 * @author Ashley Dawson <ashley.dawson@barbon.com>
 */
interface ReferenceGeneratorInterface
{
    /**
     * Generate a transaction reference
     *
     * @return string
     */
    public function generate();
}