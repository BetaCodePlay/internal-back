<?php

namespace Dotworkers\Store\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PointsTransaction
 *
 * This class allows to interact with points_transactions table
 *
 * @package Dotworkers\Store\Entities
 * @author  Eborio Linarez
 */
class PointsTransaction extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'points_transactions';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['points_wallet_id', 'amount', 'balance', 'provider_id', 'transaction_type_id', 'currency_iso', 'whitelabel_id', 'provider_transaction'];
}
