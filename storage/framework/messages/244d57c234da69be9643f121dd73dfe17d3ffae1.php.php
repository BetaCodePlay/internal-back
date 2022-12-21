<?php

namespace App\Core\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProviderType
 *
 * This class allows to interact with provider_types table
 *
 * @package App\Core\Entities
 * @author  Damelys Espinoza
 */
class ProviderType extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'provider_types';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['id', 'name'];

    /**
     * Timestamps
     *
     * @var bool
     */
    public $timestamps = false;
}