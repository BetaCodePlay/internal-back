<?php

namespace Dotworkers\Configurations\Enums;

/**
 * Class Email
 *
 * This class allows to define response codes
 *
 * @package Dotworkers\Configurations\Enums
 * @author  Carlos Hurtado
 */
class EmailTypes
{   
    /**
     * Activate Account
     *
     * @var string
     */
    public static $activate_account= 1;

    /**
     * Welcome
     *
     * @var string
     */
    public static $welcome = 2;

    /**
     * Password Reset
     *
     * @var string
     */
    public static $password_reset = 3;

    /**
     * Password Reset Confirmation
     *
     * @var string
     */
    public static $password_reset_confirmation = 4;

    /**
     * Retention Day 3
     *
     * @var string
     */
    public static $retention_day_3= 5;

    /**
     * Retention Day 7
     *
     * @var string
     */
    public static $retention_day_7 = 6;

    /**
     * Retention Day 14
     *
     * @var string
     */
    public static $retention_day_14 = 7;

    /**
     * Retention Day 21
     *
     * @var string
     */
    public static $retention_day_21 = 8;

    /**
     * Retention Day 30
     *
     * @var string
     */
    public static $retention_day_30 = 9;

    /**
     * Retention Day 45
     *
     * @var string
     */
    public static $retention_day_45 = 10;

    /**
     * Credit
     *
     * @var string
     */
    public static $credit = 11;

    /**
     * Debit
     *
     * @var string
     */
    public static $debit = 12;

}