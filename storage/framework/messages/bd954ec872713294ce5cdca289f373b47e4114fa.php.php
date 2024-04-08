<?php

namespace App\Store\Entities;

use Dotworkers\Configurations\Configurations;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ActionConfiguration
 *
 * This class allows to interact with actions_configurations table
 *
 * @package App\Store\Entities
 * @author  Damelys Espinoza
 */
class ActionConfiguration extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'actions_configurations';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['action_id', 'currency_iso', 'whitelabel_id', 'data', 'provider_type_id', 'exclude_providers'];

    /**
     * Timestamps
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Cast fields
     *
     * @var array
     */
    public $casts = [
        'data' => 'array',
        'exclude_providers' => 'array'
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
