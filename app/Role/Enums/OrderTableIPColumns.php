<?php

namespace App\Role\Enums;

class OrderTableIPColumns
{
    public const IP  = 'data->ip';
    public const QUANTITY = 'users.type_user';
    public static function getOrderTableIPColumns()
    : array
    {
        return [
            0 => self::IP,
            1 => self::QUANTITY,
            2 => self::ID,
            3 => self::ACTION,
            4 => self::BALANCE,
        ];
    }
}
