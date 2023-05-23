<?php


namespace App\DotSuite\Enums;

/**
 * Class FreeSpinsStatus
 *
 * This class allows to define static free spins status
 *
 * @package App\DotSuite\Enums
 * @author  Damelys Espinoza
 */
class FreeSpinsStatus
{
    /**
     * Pending to play
     *
     * @var integer
     */
    public static $pending_to_play = 1;

    /**
     * Played
     *
     * @var integer
     */
    public static $played = 2;

    /**
     * Cancelled
     *
     * @var integer
     */
    public static $cancelled = 3;

    /**
     * Expired
     *
     * @var integer
     */
    public static $expired = 4;

    /**
     * Enable promotion
     *
     * @var integer
     */
    public static $enable = 5;

    /**
     * Disable promotion
     *
     * @var integer
     */
    public static $disable = 6;

    /**
     * Get status name
     *
     * @param int $status Document status
     * @return array|string|null
     */
    public static function getName($status)
    {
        switch ($status) {
            case self::$pending_to_play:
            {
                return _i('Pending to play');
                break;
            }
            case self::$played:
            {
                return _i('Played');
                break;
            }
            case self::$cancelled:
            {
                return _i('Cancelled');
                break;
            }
            case self::$expired:
            {
                return _i('Expired');
                break;
            }
            case self::$enable:
            {
                return _i('Enable');
                break;
            }
            default:
            {
                return _i('Disable');
                break;
            }
        }
    }
}
