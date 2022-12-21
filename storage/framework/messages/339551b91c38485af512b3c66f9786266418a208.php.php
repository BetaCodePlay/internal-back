<?php

namespace Dotworkers\Bonus\Enums;

/**
 * Class RolloversStatus
 *
 * This class define static rollovers statuses
 *
 * @package App\Bonus\Enums
 * @author  Damelys Espinoza
 */
class RolloverStatus
{
    /**
     * Pending
     *
     * @var int
     */
    public static $pending = 1;

    /**
     * Completed
     *
     * @var int
     */
    public static $completed = 2;

    /**
     * Cancelled
     *
     * @var int
     */
    public static $cancelled = 3;
}