<?php

namespace Dotworkers\Bonus\Enums;

/**
 * Class CampaignParticipationStatus
 *
 * This class define static campaign participation statuses
 *
 * @package App\Bonus\Enums
 * @author  Eborio Linárez
 */
class CampaignParticipationStatus
{
    /**
     * Assigned
     *
     * @var int
     */
    public static $assigned = 1;

    /**
     * In use
     *
     * @var int
     */
    public static $in_use = 2;

    /**
     * Canceled by user
     *
     * @var int
     */
    public static $canceled_by_user = 3;

    /**
     * Canceled by administrator
     *
     * @var int
     */
    public static $canceled_by_administrator = 4;

    /**
     * Completed rollover
     *
     * @var int
     */
    public static $completed_rollover = 5;

    /**
     * Expired rollover
     *
     * @var int
     */
    public static $expired_rollover = 6;

    /**
     * Canceled by withdrawal
     *
     * @var int
     */
    public static $canceled_by_withdrawal = 7;

    /**
     * Assigned for bet
     *
     * @var int
     */
    public static $assigned_for_bet = 8;
}