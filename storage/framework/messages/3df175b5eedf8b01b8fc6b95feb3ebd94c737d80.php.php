<?php

namespace Dotworkers\Bonus\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Rollover
 *
 * This class allows to interact with rollovers table
 *
 * @package Dotworkers\Bonus\Entities
 * @author  Damelys Espinoza
 */
class Rollover extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'rollovers';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['campaign_id', 'rollover_type_id', 'user_id', 'total', 'target', 'deposit', 'status', 'bonus', 'final_amount', 'converted'];

    /**
     * Dates cast
     *
     * @var string[]
     */
    protected $dates = [
        'expiration_date'
    ];
}