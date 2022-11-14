<?php

namespace App\Store\Entities;

use Dotworkers\Configurations\Configurations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class StoreReward
 *
 * This class allows to interact with store_rewards table
 *
 * @package App\Store\Entities
 * @author  Damelys Espinoza
 */
class StoreReward extends Model
{
    /**
     * SofDeletes
     */
    use SoftDeletes;

    /**
     * Table
     *
     * @var string
     */
    protected $table = 'store_rewards';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'points', 'image', 'data', 'currency_iso', 'whitelabel_id', 'quantity', 'status', 'start_date', 'end_date', 'language', 'deleted_at', 'category_id', 'slug'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['start_date', 'end_date', 'deleted_at'];

    /**
     * Cast fields
     *
     * @var array
     */
    public $casts = [
        'data' => 'array'
    ];

    /**
     * Get data attribute
     *
     * @param array $data credential data
     * @return mixed
     */
    public function getDataAttribute($data)
    {
        return json_decode($data);
    }

    /**
     * Scope whitelabel
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    public function scopeWhitelabel($query)
    {
        return $query->where('whitelabel_id', Configurations::getWhitelabel());
    }
}
