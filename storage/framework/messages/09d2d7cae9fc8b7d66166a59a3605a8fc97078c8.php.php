<?php


namespace Dotworkers\Bonus\Enums;


/**
 * Class AllocationCriteria
 *
 * This class allows to define static allocation criteria
 *
 * @package App\Bonus\Enums
 * @author  Damelys Espinoza
 */
class AllocationCriteria
{
    /**
     * Registration
     *
     * @var int
     */
    public static $registration = 1;

    /**
     * Deposit
     *
     * @var int
     */
    public static $deposit = 2;

    /**
     * Complete profile
     *
     * @var int
     */
    public static $complete_profile = 3;

    /**
     * Bet
     *
     * @var int
     */
    public static $bet = 4;

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
            case self::$registration:
            {
                $name = _i('Registration');
                break;
            }
            case self::$deposit:
            {
                $name = _i('Deposit');
                break;
            }
            case self::$complete_profile:
            {
                $name = _i('Complete profile');
                break;
            }
            case self::$bet:
            {
                $name = _i('Bet');
                break;
            }
        }
        return $name;
    }
}