<?php

namespace Dotworkers\Configurations\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EmailConfigurations
 *
 * This class allows to interact with email_configurations table
 *
 * @package Dotworkers\Configurations\Entities
 * @author  Eborio Linarez
 */
class EmailType extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'email_types';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['name', 'title', 'subtitle', 'content', 'button', 'footer', 'language', 'data'];

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
     * @param array $data email configurations data
     * @return mixed
     */
    public function getDataAttribute($data)
    {
        return json_decode($data);
    }
}
