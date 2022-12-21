<?php

namespace App\DotSuite\Entities;

use Dotworkers\Configurations\Configurations;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DotSuiteTickets
 *
 *  This class allows to interact with dotsuite_tickets table
 *
 * @package App\DotSuite\Entities
 * @author Carlos Hurtado
 */
class DotSuiteTicket extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'dotsuite_tickets';

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
    protected $fillable = ['amount', 'transaction_type_id', 'wallet_transaction', 'balance', 'user_id', 'currency_iso', 'whitelabel_id', 'provider_id', 'type', 'extra_data', 'dotsuite_game_id', 'round', 'created_at', 'updated_at'];

    /**
     * Cast fields
     *
     * @var array
     */
    public $casts = [
        'extra_data' => 'array'
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
