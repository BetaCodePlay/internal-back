<?php

namespace App\Security\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ExcludeRolePermissionsUser
 *
 * This class define exclude_role_permissions_user table properties
 *
 * @package App\Security\Entities
 * @author Mayinis Torrealba
 */
class ExcludeRolePermissionsUser extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'exclude_role_permissions_user';

   /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['user_id', 'data', 'exclude'];

}
