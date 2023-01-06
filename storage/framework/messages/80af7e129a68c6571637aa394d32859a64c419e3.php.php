<?php


namespace Dotworkers\Store\Entities;


use Illuminate\Database\Eloquent\Model;

/**
 * Class ActionConfiguration
 *
 * This class allows to interact with actions_configurations table
 *
 * @package Dotworkers\Store\Entities
 * @author  Damelys Espinoza
 *
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
     * Cast fields
     *
     * @var array
     */
    public $casts = [
        'data' => 'array',
        'exclude_providers' => 'array',
    ];

    /**
     * Get data attribute
     *
     * @param string $data JSON string
     * @return mixed
     */
    public function getDataAttribute($data)
    {
        return json_decode($data);
    }

    /**
     * Get exclude providers attribute
     *
     * @param string $exludeProviders JSON string
     * @return mixed
     */
    public function getExcludeProvidersAttribute($exludeProviders)
    {
        return json_decode($exludeProviders);
    }
}