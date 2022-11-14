<?php

namespace  Dotworkers\Configurations\Enums;

/**
 * Class BetPayCodes
 *
 * This class allows to define static BetPay responses codes
 *
 * @package  Dotworkers\Configurations\Enums;
 * @author  Eborio Linarez
 */
class BetPayCodes
{
    /**
     * Credit code in use
     *
     * @var int
     */
    public static $credit_code_in_use = 400;

    /**
     * Credit code not found
     *
     * @var int
     */
    public static $credit_code_not_found = 401;

    /**
     * User account not found
     *
     * @var int
     */
    public static $user_account_not_found = 402;

    /**
     * Client account not found
     *
     * @var int
     */
    public static $client_account_not_found = 403;

    /**
     * Pending transactions
     *
     * @var int
     */
    public static $pending_transactions = 404;

    /**
     * Used reference
     *
     * @var int
     */
    public static $used_reference = 405;

    /**
     * Duplicate account
     *
     * @var int
     */
    public static $duplicate_account = 406;

    /**
     * Amount less than
     *
     * @var int
     */
    public static $amount_less_than = 407;

    /**
     * Amount greater than
     *
     * @var int
     */
    public static $amount_greater_than = 408;

    /**
     * Amount between values
     *
     * @var int
     */
    public static $amount_between_values = 409;

    /**
     * Duplicate payment
     *
     * @var int
     */
    public static $duplicate_payment = 410;

    /**
     * Invalid DNI
     *
     * @var int
     */
    public static $invalid_dni = 411;

    /**
     * Insufficient balance
     *
     * @var int
     */
    public static $insufficient_balance = 412;

    /**
     * Transaction not found
     *
     * @var int
     */
    public static $transaction_not_found = 413;

    /**
     * Currency not active
     *
     * @var int
     */
    public static $currency_not_active = 414;

    /**
     * Invalid date
     *
     * @var int
     */
    public static $invalid_date = 415;

    /**
     * Internal error
     *
     * @var int
     */
    public static $internal_error = 500;

    /**
     * Api message
     *
     * @var int
     */
    public static $api_message = 415;
}
