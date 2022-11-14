<?php


namespace App\CRM\Entities;

use Dotworkers\Configurations\Configurations;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Segment
 *
 * This class allows to interact with segments entity
 *
 * @package App\CRM\Entities
 * @author  Damelys Espinoza
 */
class Segment extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'segments';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['id', 'name', 'description', 'data', 'whitelabel_id', 'created_at', 'updated_at', 'filter', 'status'];

    /**
     * Casts
     *
     * @var string[]
     */
    protected $casts = [
        'data' => 'array',
        'filter' => 'array'
    ];

    /**
     * Get data attribute
     *
     * @param string $data Data
     * @return mixed
     */
    public function getDataAttribute($data)
    {
        return json_decode($data);
    }

    /**
     * Get filter attribute
     *
     * @param string $filter Filter
     * @return mixed
     */
    public function getFilterAttribute($filter)
    {
        return json_decode($filter);
    }

    /**
     * Scope whitelabel
     *
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @return mixed
     */
    public function scopeWhitelabel($query)
    {
        return $query->where('whitelabel_id', Configurations::getWhitelabel());
    }
}
