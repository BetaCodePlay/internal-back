<?php

namespace Dotworkers\Audits\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Audit
 *
 * This class allows to interact with audits table
 *
 * @package Dotworkers\Audits\Entities
 * @author  Orlando Bravo
 */
class Audit extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'audits';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'audit_type_id', 'data', 'whitelabel_id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' =>  'array',
    ];
}
