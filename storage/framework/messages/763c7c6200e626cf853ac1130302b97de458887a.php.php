<?php


namespace Dotworkers\Security\Entities;

use Illuminate\Database\Eloquent\Model;
use Dotworkers\Security\Entities\RoleDot;
use Dotworkers\Security\Entities\PermissionDot;

/**
 * Class User
 *
 * This class allows to interact with user table
 *
 * @package Dotworkers\Security\Entities
 * @author  Mayinis Torrealba
 */
class User extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'email', 'password', 'uuid', 'status', 'whitelabel_id', 'ip', 'tester', 'web_register', 'referral_code', 'reference', 'last_login', 'last_deposit', 'last_deposit_amount', 'last_debit', 'last_debit_amount'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * A model may have multiple roles.
     */
    public function roles()
    {
        return $this->morphToMany(RoleDot::class,'model','user_has_roles','model_id','role_id');
    }

    /**
     * A model may have multiple permissions.
     */
    public function permissions()
    {
        return $this->morphToMany(PermissionDot::class,'model','user_has_permissions','model_id','permission_id');
    }

}