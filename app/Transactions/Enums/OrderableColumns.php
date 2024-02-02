<?php

namespace App\Transactions\Enums;

class OrderableColumns
{
    public const ID                   = 'transactions.id';
    public const AMOUNT               = 'transactions.amount';
    public const TRANSACTION_TYPE_ID = 'transactions.transaction_type_id';
    public const CREATED_AT           = 'transactions.created_at';
    public const PROVIDER_ID          = 'transactions.provider_id';
    public const DATA                 = 'transactions.data';
    public const TRANSACTION_STATUS_ID = 'transactions.transaction_status_id';

    public static function getOrderableColumns(): array
    {
        return [
            0 => self::ID,
            1 => self::AMOUNT,
            2 => self::TRANSACTION_TYPE_ID,
            3 => self::CREATED_AT,
            4 => self::PROVIDER_ID,
            5 => self::DATA,
            6 => self::TRANSACTION_STATUS_ID,
        ];
    }
}
