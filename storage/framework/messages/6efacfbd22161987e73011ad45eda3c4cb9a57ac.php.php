<?php

namespace App\Core\Enums;

/**
 * Class TransactionNotificationsTypes
 *
 * This class allows to define static transaction notifications types
 *
 * @package App\Core\Enums
 * @author  Eborio Linárez
 */
class TransactionNotificationsTypes
{
    /**
     * Transaction greater than allowed
     *
     * @var int
     */
    public static $transaction_greater_than_allowed = 1;

    /**
     * Transaction greater than available amount
     *
     * @var int
     */
    public static $transaction_greater_than_available_amount = 2;
}
