<?php

namespace App\Users\Entities;

use Dotworkers\Configurations\Configurations;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 *
 * Class to define the users_temp table attributes
 *
 * @package App\Users\Entities
 * @author  Eborio Linarez
 */
class UserTemp extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'users_temp';

    /**
     * Primary key
     *
     * @var string
     */
    protected $primaryKey = 'username';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'email', 'password', 'token', 'ip', 'currency_iso', 'whitelabel_id'];

    /**
     * Incrementing
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Scope whitelabel
     *
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @return mixed
     */
    public function scopeWhitelabel($query)
    {
        return $query->where('whitelabel_id', Configurations::getWhitelabel());
    }

    /**
     * Set password attribute
     *
     * @param string $password Password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }
}
