<?php

namespace App\Core\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Provider
 *
 * This class allows to interact with providers table
 *
 * @package App\Core\Entities
 * @author  Eborio Linarez
 */
class Provider extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'providers';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [ 'name', 'provider_type_id', 'status', 'tickets_table', 'games_table', 'betpay_id'];

    /**
     * Timestamps
     *
     * @var bool
     */
    public $timestamps = false;

}
