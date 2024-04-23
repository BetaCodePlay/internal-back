<?php

namespace Dotworkers\Wallet\Enums;

/**
 * Class Actions
 *
 * This class allows to define static actions
 *
 * @package Dotworkers\Wallet\Enums
 * @author  Eborio Linarez
 * @author  Gabriel Santiago
 */
class Actions
{
    /**
     * Generic actions (spins, bets, deposits, withdrawals)
     *
     * @var int
     */
    public static $generic = 1;

    /**
     * Manual actions
     *
     * @var int
     */
    public static $manual = 2;

    /**
     * Lock actions
     *
     * @var int
     */
    public static $lock = 3;

    /**
     * Unlock actions
     *
     * @var int
     */
    public static $unlock = 4;
}
