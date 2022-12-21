<?php

namespace Dotworkers\Store\Enums;

/**
 * Class TransactionTypes
 *
 * This class allows to define static transaction types
 *
 * @package Dotworkers\Store\Enums
 * @author  Eborio Linarez
 */
class TransactionTypes
{
    /**
     * Credit transactions
     *
     * @var int
     */
    public static $credit = 1;

    /**
     * Debit transactions
     *
     * @var int
     */
    public static $debit = 2;
}