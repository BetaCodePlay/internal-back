<?php

namespace App\Role\Enums;

class OrderableColumns
{
    public const USERNAME  = 'users.username';
    public const TYPE_USER = 'users.type_user';
    public const ID        = 'users.id';
    public const ACTION    = 'users.action';
    public const BALANCE   = 'agent_currencies.balance';
    public static function getOrderableColumns()
    : array
    {
        return [
            0 => self::USERNAME,
            1 => self::TYPE_USER,
            2 => self::ID,
            3 => self::ACTION,
            4 => self::BALANCE,
        ];
    }
}
