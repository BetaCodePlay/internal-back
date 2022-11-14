<?php


namespace Dotworkers\Bonus\Enums;


/**
 * Class AllocationCriteria
 *
 * This class allows to define static allocation criteria
 *
 * @package App\Rollovers\Enums
 * @author  Damelys Espinoza
 */
class AllocationCriteria
{
    /**
     * Welcome bonus with deposit
     *
     * @var int
     */
    public static $welcome_bonus_with_deposit = 1;

    /**
     * Welcome bonus without deposit
     *
     * @var int
     */
    public static $welcome_bonus_without_deposit = 2;

    /**
     * Cash back bonus
     *
     * @var int
     */
    public static $cash_back_bonus = 3;

    /**
     * Birthday bonus
     *
     * @var int
     */
    public static $birthday_bonus = 4;

    /**
     * Loyalty bonus
     *
     * @var int
     */
    public static $loyalty_bonus = 5;

    /**
     * Contest winner bonus
     *
     * @var int
     */
    public static $contest_winner_bonus = 6;

    /**
     * Tournament
     *
     * @var int
     */
    public static $tournament = 7;

    /**
     * Tournament
     *
     * @var int
     */
    public static $login_bonus = 8;

    /**
     * Bonus code
     *
     * @var int
     */
    public static $bonus_code = 9;

    /**
     * Bonus code with deposit
     *
     * @var int
     */
    public static $bonus_code_with_deposit = 10;

    /**
     * Wallet bonus
     *
     * @var int
     */
    public static $wallet_bonus = 11;

    /**
     * Bet bonus
     *
     * @var int
     */
    public static $bet_bonus = 12;

    /**
     * Next deposit bonus
     *
     * @var int
     */
    public static $next_deposit_bonus = 13;

    /**
     * Get name
     *
     * @param int $allocationCriteria Allocation criteria ID
     * @return string|null
     */
    public static function getName($allocationCriteria)
    {
        $name = null;
        switch ($allocationCriteria) {
            case self::$welcome_bonus_with_deposit:
            {
                $name = _i('Welcome bonus with deposit');
                break;
            }
            case self::$welcome_bonus_without_deposit:
            {
                $name = _i('Welcome bonus without deposit');
                break;
            }
            case self::$cash_back_bonus:
            {
                $name = _i('Cash back bonus');
                break;
            }
            case self::$birthday_bonus:
            {
                $name = _i('Birthday bonus');
                break;
            }
            case self::$loyalty_bonus:
            {
                $name = _i('Loyalty bonus');
                break;
            }
            case self::$contest_winner_bonus:
            {
                $name = _i('Contest winner bonus');
                break;
            }
            case self::$tournament:
            {
                $name = _i('Tournament');
                break;
            }
            case self::$login_bonus:
            {
                $name = _i('Login bonus');
                break;
            }
            case self::$bonus_code:
            {
                $name = _i('Bonus code');
                break;
            }
            case self::$bonus_code_with_deposit:
            {
                $name = _i('Bonus code with number deposit');
                break;
            }
            case self::$wallet_bonus:
            {
                $name = _i('Wallet balance bonus');
                break;
            }
            case self::$bet_bonus:
            {
                $name = _i('Bet bonus');
                break;
            }
            case self::$next_deposit_bonus:
            {
                $name = _i('Next deposit bonus');
                break;
            }
        }
        return $name;
    }
}