<?php

namespace Dotworkers\Security\Entities;

use Illuminate\Database\Eloquent\Model;
use Dotworkers\Security\Entities\PermissionDot;
use Dotworkers\Security\Entities\User;

/**
 * Class RoleDot
 * This class allows to interact with roles security table
 *
 * @package Dotworkers\Security\Entities
 * @author  Mayinis Torrealba
 */
class RoleDot extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'roles_dot';


    /**
     * Relation with Permission entity
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(PermissionDot::class,'role_has_permissions','role_id','permission_id');
    }

    /**
     * A role belongs to some users of the model associated with its guard.
     *
     * Relation with Users entity
     *
     *
     */
    public function users()
    {
        return $this->morphedByMany(User::class,'model','user_has_roles','role_id','model_id');
    }

}
