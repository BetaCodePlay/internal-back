<?php

namespace Dotworkers\Security\Entities;

use Illuminate\Database\Eloquent\Model;
use Dotworkers\Security\Entities\RoleDot;
use Dotworkers\Security\Entities\User;

/**
 * Class Permission
 * This class allows to interact with permissions security table
 *
 * @package Dotworkers\Security\Entities
 * @author  Mayinis Torrealba
 */
class PermissionDot extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'permissions_dot';

     /**
     * Relation with Roles entity
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(RolesDot::class);
    }

    /**
     * A permission belongs to some users of the model associated with its guard.
     * Relation with Users entity
     *
     */
    public function users()
    {
        return $this->morphedByMany(User::class,'model','user_has_permissions','permission_id','model_id');
    }

}