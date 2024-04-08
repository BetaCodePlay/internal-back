<?php


namespace App\Store\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class StoreExchange
 *
 * This class allows to interact with store_exchanges table
 *
 * @package App\Store\Entities
 * @author  Damelys Espinoza
 */
class StoreExchange extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'store_exchanges';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['user_id', 'reward_id', 'currency_iso', 'whitelabel_id'];
}