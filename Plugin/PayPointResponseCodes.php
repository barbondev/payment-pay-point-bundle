<?php

namespace Barbondev\Payment\PayPointHostedBundle\Plugin;

/**
 * Class PayPointResponseCodes
 *
 * @package Barbondev\Payment\PayPointHostedBundle\Plugin
 * @author Ashley Dawson <ashley.dawson@barbon.com>
 */
final class PayPointResponseCodes
{
    /**
     * Response codes
     */
    const AUTHORISED = 'A';
    const NOT_AUTHORISED = 'N';
    const COMMUNICATION_PROBLEM = 'C';
    const FRAUD_CONDITION = 'F';
    const AMOUNT_NOT_SUPPLIED_OR_INVALID = 'P:A';
    const NOT_ALL_MANDATORY_PARAMETERS_SUPPLIED = 'P:X';
    const SAME_PAYMENT_PRESENTED_TWICE = 'P:P';
    const START_DATE_INVALID = 'P:S';
    const EXPIRY_DATE_INVALID = 'P:E';
    const ISSUE_NUMBER_INVALID = 'P:I';
    const FAILED_LUHN_CHECK = 'P:C';
    const CARD_TYPE_INVALID = 'P:T';
    const CUSTOMER_NAME_NOT_SUPPLIED = 'P:N';
    const MERCHANT_DOES_NOT_EXIST = 'P:M';
    const MERCHANT_FOR_CARD_TYPE_DOES_NOT_EXIST = 'P:B';
    const MERCHANT_FOR_CURRENCY_DOES_NOT_EXIST = 'P:D';
    const CV2_CODE_NOT_SUPPLIED = 'P:V';
    const TRANSACTION_TIMED_OUT = 'P:R';

    /**
     * Private constructor as this is a static namespace
     */
    private function __construct()
    {
    }
}