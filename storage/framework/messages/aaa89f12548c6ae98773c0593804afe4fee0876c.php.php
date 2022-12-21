<?php


namespace Dotworkers\Store\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class StoreTransaction
 *
 * @package Dotworkers\Store\Entities
 */
class StoreTransaction extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'store_transactions';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['points_wallet_id', 'amount', 'balance', 'provider_id', 'transaction_type_id', 'currency_iso', 'whitelabel_id', 'provider_transaction', 'user_id', 'data'];
}