<?php

namespace Dotworkers\Sessions\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Session
 *
 * This class allows to interact with sessions table
 *
 * @package Dotworkers\Sessions\Entities
 * @author  Eborio Linarez
 */
class Session extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'sessions';

    /**
     * Fillable fields
     *
     * @var string
     */
    protected $fillable = ['last_activity'];

    /**
     * Incrementing primary key
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Timestamps
     *
     * @var bool
     */
    public $timestamps = false;
}
