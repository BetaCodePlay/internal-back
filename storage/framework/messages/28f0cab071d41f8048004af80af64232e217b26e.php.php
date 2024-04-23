<?php

namespace Dotworkers\Configurations\Enums;

/**
 * Class TransactionStatus
 *
 * This class allows to define transactions status
 *
 * @package Dotworkers\Configurations\Enums
 * @author  Orlando Bravo
 */
class TransactionStatus
{
    /**
     * Pending
     *
     * @var int
     */
    public static $pending = 1;

    /**
     * Approved
     *
     * @var int
     */
    public static $approved = 2;

    /**
     * Rejected
     *
     * @var int
     */
    public static $rejected = 3;

    /**
     * Processing
     *
     * @var int
     */
    public static $processing = 4;

    /**
     * Rejected by bank
     *
     * @var int
     */
    public static $rejected_by_bank = 5;

    /**
     * Expired
     *
     * @var int
     */
    public static $expired = 6;
}
