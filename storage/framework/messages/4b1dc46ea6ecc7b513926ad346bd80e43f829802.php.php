<?php

namespace Dotworkers\Bonus\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 *
 * This class allows to interact with users table
 *
 * @package Dotworkers\Bonus\Entities
 * @author  Eborio Linarez
 */
class User extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'users';
}
