<?php

namespace App\Notifications\Enums;

/**
 * Class NotificationTypes
 *
 * This class allows to define email templates status
 *
 * @package App\Notifications\Enums
 * @author  Carlos Hurtado
 */
class NotificationTypes
{
    /**
     * User
     *
     * @var int
     */
    public static $user = 1;

    /**
     * Group
     *
     * @var int
     */
    public static $group = 2;

    /**
     * All users
     *
     * @var int
     */
    public static $all_users = 3;

    /**
     * Segment
     *
     * @var int
     */
    public static $segment = 4;

    /**
     * Excel
     *
     * @var int
     */
    public static $excel = 5;
}
