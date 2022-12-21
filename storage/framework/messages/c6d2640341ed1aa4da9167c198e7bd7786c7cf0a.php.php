<?php


namespace Dotworkers\Configurations\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 *
 * This class allows to interact with user table
 *
 * @package Dotworkers\Configurations\Entities
 * @author  Derluin Gonzalez
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

}