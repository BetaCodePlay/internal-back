<?php

namespace App\BonusSystem\Entities;

use Dotworkers\Configurations\Configurations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Campaign
 *
 * This class define campaigns table properties
 *
 * @package App\BonusSystem\Entities
 * @author Damelys Espinoza
 */
class Campaign extends Model
{
    use SoftDeletes;

    /**
     * Table
     *
     * @var string
     */
    protected $table = 'campaigns';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['name', 'data', 'device', 'status', 'start_date', 'end_date', 'currency_iso', 'whitelabel_id', 'translations', 'version', 'parent_campaign', 'original_campaign', 'bonus_type_id'];

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
        'data' => 'array',
        'translations' => 'array'
    ];

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

    /**
     * Get data attribute
     *
     * @param string $data Campaign data
     * @return mixed
     */
    public function getDataAttribute($data)
    {
        return json_decode($data);
    }

    /**
     * Relationship with Rollover entity
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rolloverTypes()
    {
        return $this->hasMany(RolloverType::class);
    }

    /**
     * Get data attribute
     *
     * @param string $translations Campaign translations
     * @return mixed
     */
    public function getTranslationsAttribute($translations)
    {
        return json_decode($translations);
    }
}
