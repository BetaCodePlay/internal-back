<?php


namespace App\DotSuite\Entities;


use Dotworkers\Configurations\Configurations;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DotSuiteFreeSpin
 *
 *  This class allows to interact with dotsuite_free_spins table
 *
 * @package App\DotSuite\Entities
 * @author  Damelys Espinoza
 */
class DotSuiteFreeSpin extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'dotsuite_free_spins';

    /**
     * Primary key
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['users', 'games_id', 'currency_iso', 'whitelabel_id', 'provider_id', 'free_spins', 'data', 'status', 'created_at', 'update_at'];

    /**
     * Cast fields
     *
     * @var array
     */
    public $casts = [
        'users' => 'array',
        'games_id' => 'array',
        'data' => 'array'
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
     * @param string $data Dotsuite data
     * @return mixed
     */
    public function getDataAttribute($data)
    {
        return json_decode($data);
    }
}
