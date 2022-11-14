<?php

namespace Dotworkers\Store\Entities;


use Illuminate\Database\Eloquent\Model;

/**
 * Class StoreExchange
 *
 * This class allows to interact with store_exchanges table
 *
 * @package Dotworkers\Store\Entities;
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
    protected $fillable = ['user_id', 'reward_id', 'currency_iso', 'whitelabel_id', 'points', 'data'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];

    /**
     * Get data attribute
     *
     * @param array $data Exchange data
     * @return mixed
     */
    public function getDataAttribute($data)
    {
        return json_decode($data);
    }
}