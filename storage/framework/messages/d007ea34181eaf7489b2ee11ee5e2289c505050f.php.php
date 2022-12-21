<?php

namespace Dotworkers\Configurations\Enums;

/**
 * Class Whitelabel Status
 *
 * This class allows to define static whitelabels status
 *
 * @package Dotworkers\Configurations\Enums
 * @author  Eborio Linarez
 */
class WhitelabelStatus
{
    /**
     * Active
     *
     * @var int
     */
    public static $active = 1;

    /**
     * Suspended
     *
     * @var int
     */
    public static $suspended = 2;

    /**
     * Whitelabel maintenance
     *
     * @var int
     */
    public static $whitelabel_maintenance = 3;

    /**
     * Development
     *
     * @var int
     */
    public static $development = 4;

    /**
     * Test
     *
     * @var int
     */
    public static $test = 5;

    /**
     * Whitelabel and Dotpanel maintenance
     *
     * @var int
     */
    public static $whitelabel_dotpanel_maintenance = 6;
}
