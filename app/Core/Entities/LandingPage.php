<?php


namespace App\Core\Entities;

use Dotworkers\Configurations\Configurations;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Audit
 *
 * Class to define the landing page table attributes
 *
 * @package App\Core\Entities
 * @author  Orlando Bravo
 */
class LandingPage extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'landing_pages';
    /**
     * Primary key
     *
     * @var array
     */
    protected $fillable = ['name', 'whitelabel_id', 'currency_iso', 'language', 'status', 'start_date', 'end_date', 'data'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['start_date', 'end_date'];

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
