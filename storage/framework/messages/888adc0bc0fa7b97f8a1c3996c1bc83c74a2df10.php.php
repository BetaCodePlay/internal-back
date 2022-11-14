<?php

namespace Dotworkers\Configurations\Enums;

use Dotworkers\Configurations\Configurations;

/**
 * Class ProviderTypes
 *
 * This class allows to define static provider types
 *
 * @package Dotworkers\Configurations\Enums
 * @author  Eborio Linarez
 */
class ProviderTypes
{
    /**
     * Dotworkers
     *
     * @var int
     */
    public static $dotworkers = 1;

    /**
     * Payment
     *
     * @var int
     */
    public static $payment = 2;

    /**
     * Casino
     *
     * @var int
     */
    public static $casino = 3;

    /**
     * Live Casino
     *
     * @var int
     */
    public static $live_casino = 4;

    /**
     * Virtual
     *
     * @var int
     */
    public static $virtual = 5;

    /**
     * Manual adjustments
     *
     * @var int
     */
    public static $manual_adjustments = 6;

    /**
     * Bonus transactions
     *
     * @var int
     */
    public static $bonus_transactions = 7;

    /**
     * Agents
     *
     * @var int
     */
    public static $agents = 8;

    /**
     * Store
     *
     * @var int
     */
    public static $store = 9;

    /**
     * SportBook
     *
     * @var int
     */
    public static $sportbook = 10;

    /**
     * RaceBook
     *
     * @var int
     */
    public static $racebook = 11;

    /**
     * Live games
     *
     * @var int
     */
    public static $live_games = 12;

    /**
     * Poker
     *
     * @var int
     */
    public static $poker = 13;

    /**
     * Notifications
     *
     * @var int
     */
    public static $notifications = 14;

    /**
     * Get provider type name
     *
     * @param int $type Provider type
     * @return mixed|string|null
     */
    public static function getName($type)
    {
        $name = null;
        switch ($type) {
            case self::$dotworkers:
            {
                $name = Configurations::getWhitelabelDescription();
                break;
            }
            case self::$payment:
            {
                $name = _i('Payment method');
                break;
            }
            case self::$casino:
            {
                $name = _i('Casino');
                break;
            }
            case self::$live_casino:
            {
                $name = _i('Live casino');
                break;
            }
            case self::$virtual:
            {
                $name = _i('Virtual');
                break;
            }
            case self::$manual_adjustments:
            {
                $name = _i('Manual adjustments');
                break;
            }
            case self::$bonus_transactions:
            {
                $name = _i('Bonus');
                break;
            }
            case self::$agents:
            {
                $name = _i('Agents');
                break;
            }
            case self::$store:
            {
                $name = _i('Store');
                break;
            }
            case self::$sportbook:
            {
                $name = _i('SportBook');
                break;
            }
            case self::$racebook:
            {
                $name = _i('RaceBook');
                break;
            }
            case self::$live_games:
            {
                $name = _i('Live games');
                break;
            }
            case self::$poker:
            {
                $name = _i('Poker');
                break;
            }
            case self::$notifications:
            {
                $name = _i('Notifications');
                break;
            }
        }
        return $name;
    }
}
