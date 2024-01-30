<?php

namespace App\Role\Enums;

class OrderTableIPColumns
{
    public const IP  = 'data->ip';
    public const QUANTITY = 'quantity';
    public static function getOrderTableIPColumns()
    : array
    {
        return [
            0 => self::IP,
            1 => self::QUANTITY
        ];
    }
}
