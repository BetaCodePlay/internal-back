<?php

namespace App\Whitelabels\Enums;

/**
 * Class Status
 *
 * This class allows to define static whitelabels status
 *
 * @package App\Whitelabels\Enums
 * @author  Eborio Linarez
 */
class Status
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

    /**
     * Get name
     *
     * @param int $status Whitelabel status
     * @return string|null
     */
    public static function getName($status)
    {
        $name = null;
        switch ($status) {
            case self::$active: {
                $name = _i('Active');
                break;
            }
            case self::$suspended: {
                $name = _i('Suspended');
                break;
            }
            case self::$whitelabel_maintenance: {
                $name = _i('Whitelabel under maintenance');
                break;
            }
            case self::$development: {
                $name = _i('Development');
                break;
            }
            case self::$test: {
                $name = _i('Test');
                break;
            }
            case self::$whitelabel_dotpanel_maintenance: {
                $name = _i('Whitelabel and Dotpanel under maintenance');
                break;
            }
        }
        return $name;
    }
}
