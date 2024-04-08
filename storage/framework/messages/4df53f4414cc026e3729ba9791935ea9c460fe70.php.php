<?php

namespace App\Core\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ManualExchange
 *
 * Manual exchanges
 *
 * @package App\Core\Entities
 * @author  Eborio Linarez
 */
class ManualExchange extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'manual_exchanges';

    /**
     * Fillable fields
     *
     * @var string[]
     */
    protected $fillable = ['amount'];
}
