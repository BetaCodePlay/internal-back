<?php

namespace Dotworkers\Store\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PointsWallet
 *
 * This class allows to interact with points_wallets table
 *
 * @package Dotworkers\Store\Entities
 * @author  Eborio Linarez
 */
class PointsWallet extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'points_wallets';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['user_id', 'currency_iso', 'balance'];
}
