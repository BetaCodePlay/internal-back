<?php

namespace App\Users\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Profile
 *
 * This class allows to interact with profiles table
 *
 * @package App\Users\Entities
 * @author  Eborio Linarez
 */
class Profile extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'profiles';

    /**
     * Primary key
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * Incrementing
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['dni', 'first_name', 'last_name', 'gender', 'level', 'country_iso', 'timezone', 'address', 'phone', 'birth_date', 'state', 'city'];
}
