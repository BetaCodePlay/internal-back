<?php

namespace App\Audits\Enums;

/**
 * Class AuditTypes
 *
 * This class allows to define static audit types
 *
 * @package App\Audits\Enums
 * @author  Gabriel Santiago
 */
class AuditTypes
{
    /**
     * Login
     *
     * @var integer
     */
    public static $login = 1;

    /**
     * Dotpanel login
     *
     * @var integer
     */
    public static $dotpanel_login = 2;

    /**
     * User modification
     *
     * @var integer
     */
    public static $user_modification = 3;

    /**
     * User creation
     *
     * @var integer
     */
    public static $user_creation = 4;

    /**
     * User status
     *
     * @var integer
     */
    public static $user_status = 5;

    /**
     * User password
     *
     * @var integer
     */
    public static $user_password = 6;

    /**
     * Support login
     *
     * @var integer
     */
    public static $support_login = 7;

    /**
     * Manual transactions
     *
     * @var integer
     */
    public static $manual_transactions = 8;

    /**
     * Manual adjustments
     *
     * @var integer
     */
    public static $manual_adjustments = 9;

    /**
     * Bonus transactions
     *
     * @var integer
     */
    public static $bonus_transactions = 10;

    /**
     * Points Transactions
     *
     * @var integer
     */
    public static $points_transactions = 11;

    /**
     * Agent user password
     *
     * @var integer
     */
    public static $agent_user_password = 12;

    /**
     * Exclude provider
     *
     * @var integer
     */
    public static $exclude_provider = 13;

    /**
     * Document verification
     *
     * @var integer
     */
    public static $document_verification = 14;

    /**
     * Slider Creation
     *
     * @var integer
     */
    public static $slider_creation = 15;

    /**
     * Slider Modidication
     *
     * @var integer
     */
    public static $slider_modification = 16;

    /**
     * Image Creation
     *
     * @var integer
     */
    public static $image_creation = 17;

    /**
     * Image Modidication
     *
     * @var integer
     */
    public static $image_modification = 18;

    /**
     * Lobbys recommended creation
     *
     * @var integer
     */
    public static $lobbys_recommended_creation = 19;

    /**
     * Lobbys recommended modification
     *
     * @var integer
     */
    public static $lobbys_recommended_modification = 20;

     /**
     * Posts creation
     *
     * @var integer
     */
    public static $posts_creation = 21;

    /**
     * Posts modification;
     *
     * @var integer
     */
    public static $posts_modification = 22;

     /**
     * Segments creation
     *
     * @var integer
     */
    public static $segments_creation = 23;

    /**
     * Segments modification;
     *
     * @var integer
     */
    public static $segments_modification = 24;

     /**
     * Email template creation
     *
     * @var integer
     */
    public static $email_template_creation = 25;

    /**
     *  Email template modification;
     *
     * @var integer
     */
    public static $email_template_modification = 26;

     /**
     * Marketing Campaigns creation
     *
     * @var integer
     */
    public static $marketing_campaigns_creation = 27;

    /**
     * Marketing Campaigns modification;
     *
     * @var integer
     */
    public static $marketing_campaigns_modification = 28;

    /**
     * Notification creation
     *
     * @var integer
     */
    public static $notification_creation = 29;

    /**
     * Notification modification;
     *
     * @var integer
     */
    public static $notification_modification = 30;

    /**
     * Featured Games;
     *
     * @var integer
     */
    public static $featured_games = 31;

    /**
     * Store Creation;
     *
     * @var integer
     */
    public static $store_creation = 32;

     /**
     * Store Modification;
     *
     * @var integer
     */
    public static $store_modification = 33;

     /**
     * Pages;
     *
     * @var integer
     */
    public static $pages = 34;

    /**
     * Agent creation;
     *
     * @var integer
     */
    public static $agent_creation = 35;

    /**
     * Agent user status;
     *
     * @var integer
     */
    public static $agent_user_status = 36;

    /**
     * Transaction debit;
     *
     * @var integer
     */
    public static $transaction_debit = 37;

    /**
     * Transaction credit;
     *
     * @var integer
     */
    public static $transaction_credit = 38;

    /**
     * Player creation;
     *
     * @var integer
     */
    public static $player_creation = 39;
}

