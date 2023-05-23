<?php


namespace App\CRM\Enums;

/**
 * Class MarketingCampaignsStatus
 *
 * This class allows to define marketing campaigns status
 *
 * @package App\CRM\Enums
 * @author  Damelys Espinoza
 */
class MarketingCampaignsStatus
{
    /**
     * Pending
     *
     * @var int
     */
    public static $pending = 1;

    /**
     * Send
     *
     * @var int
     */
    public static $sent = 2;

    /**
     * Cancelled
     *
     * @var int
     */
    public static $cancelled = 3;
}
