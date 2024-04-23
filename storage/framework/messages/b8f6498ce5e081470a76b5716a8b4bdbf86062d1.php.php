<?php

namespace Dotworkers\Notifications\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProviderNotification
 *
 * This class allows to interact with provider notification table
 *
 * @package Dotworkers\Notifications\Entities
 * @author  Orlando Bravo
 */
class ProviderNotification extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'provider_notifications';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'amount', 'currency_iso', 'whitelabel_id', 'provider_id', 'data'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' =>  'array',
    ];
}
