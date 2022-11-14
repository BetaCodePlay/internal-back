<?php

namespace App\Core\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Country
 *
 * This class allows to interact with countries table
 *
 * @package App\Core\Entities
 * @author  Eborio Linarez
 */
class Country extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'countries';

    /**
     * Primary key
     *
     * @var string
     */
    protected $primaryKey = 'iso';

    /**
     * Incrementing
     *
     * @var bool
     */
    public $incrementing = false;
}
