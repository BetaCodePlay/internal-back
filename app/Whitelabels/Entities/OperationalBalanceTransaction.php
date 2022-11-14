<?php

namespace App\Whitelabels\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class OperationalBalanceTransaction
 *
 * This class allows to interact with operational_balance_transactions table
 *
 * @package App\Whitelabels\Entities
 * @author  Eborio Linárez
 */
class OperationalBalanceTransaction extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'operational_balance_transactions';

    /**
     * Fillable fields
     *
     * @var string[]
     */
    protected $fillable = ['amount', 'user_id', 'operator', 'provider_id', 'whitelabel_id', 'currency_iso', 'transaction_type_id'];
}
