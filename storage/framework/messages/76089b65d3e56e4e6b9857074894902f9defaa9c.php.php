<?php

namespace App\Wallets\Entities;


use Illuminate\Database\Eloquent\Model;

/**
 * Class Wallet
 *
 * Class to define the wallets table attributes
 *
 * @package App\Wallets\Entities
 * @author  Damelys Espinoza
 */
class Wallet extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'wallets';

    /**
     * Primary key for the table
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Connection
     *
     * @var string
     */
    protected $connection = 'wallet';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'currency_iso', 'quantity', 'uuid', 'balance', 'balance_locked'];
}