<?php


namespace App\BonusSystem\Entities;


use Dotworkers\Configurations\Configurations;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RolloverType
 *
 * This class define rollover_types table properties
 *
 * @package App\BonusSystem\Entities
 * @author Damelys Espinoza
 */
class RolloverType extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'rollover_types';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['multiplier', 'campaign_id', 'exclude_providers', 'provider_type_id', 'created_at', 'updated_at', 'days', 'include_deposit'];

    /**
     * Cast fields
     *
     * @var array
     */
    public $casts = [
        'exclude_providers' => 'array'
    ];
}
