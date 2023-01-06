<?php

namespace App\Users\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserCurrency
 *
 * Class to define the user_currencies table attributes
 *
 * @package App\Users\Entities
 * @author  Eborio Linarez
 */
class UserCurrency extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'user_currencies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'currency_iso', 'wallet_id', 'default'];
}
