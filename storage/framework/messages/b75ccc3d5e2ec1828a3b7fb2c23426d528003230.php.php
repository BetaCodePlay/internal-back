<?php

namespace App\Core\Enums;

/**
 * Class ProductsLimits
 *
 * This class allows to define products limits
 *
 * @package App\Core\Enums
 * @author  Eborio Linarez
 */
class ProductsLimits
{
    /**
     * SportBook min bet
     *
     * @var int
     */
    public static $sportbook_min_bet = 1;

    /**
     * SportBook max bet
     *
     * @var int
     */
    public static $sportbook_max_bet = 2;

    /**
     * SportBook max selections
     *
     * @var int
     */
    public static $sportbook_max_selections = 3;

    /**
     * SportBook max selections not favorites
     *
     * @var int
     */
    public static $sportbook_max_selections_not_favorites = 4;

    /**
     * SportBook straight bet limit
     *
     * @var int
     */
    public static $sportbook_straight_bet_limit = 5;

    /**
     * SportBook parlay bet limit
     *
     * @var int
     */
    public static $parlay_bet_limit = 6;

    /**
     * Get page name
     *
     * @param int $page Page ID
     * @return array|string|null
     */
    public static function getName($page)
    {
        switch ($page) {
            case self::$sportbook_min_bet:
            {
                return _i('Min bet');
                break;
            }
            case self::$sportbook_max_bet:
            {
                return _i('Max bet');
                break;
            }
            case self::$sportbook_max_selections:
            {
                return _i('Max selections');
                break;
            }
            case self::$sportbook_max_selections_not_favorites:
            {
                return _i('Max selections not favorites');
                break;
            }
            case self::$sportbook_straight_bet_limit:
            {
                return _i('Straight bet limit');
                break;
            }
            case self::$parlay_bet_limit:
            {
                return _i('Parlay bet limit');
                break;
            }
            default:
            {
                return _i('Undefined');
                break;
            }
        }
    }
}
