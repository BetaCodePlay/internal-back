<?php

namespace Dotworkers\Bonus\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Campaign
 *
 * This class allows to interact with campaigns table
 *
 * @package Dotworkers\Bonus\Entities
 * @author  Damelys Espinoza
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
    protected $fillable = ['name', 'data', 'device', 'status', 'allocation_criteria_id', 'currency_iso', 'whitelabel_id', 'description'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['start_date', 'end_date'];

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