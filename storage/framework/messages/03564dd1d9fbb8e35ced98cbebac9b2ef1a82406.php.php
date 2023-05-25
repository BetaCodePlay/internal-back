<?php

namespace Dotworkers\Configurations\Enums;

/**
 * Class TransactionTypes
 *
 * This class allows to define static transaction types
 *
 * @package Dotworkers\Configurations\Enums
 * @author  Eborio Linarez
 * @author  Gabriel Santiago
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
