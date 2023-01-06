<?php

namespace Dotworkers\Security\Entities;

use Illuminate\Database\Eloquent\Model;
use Dotworkers\Security\Entities\Role;

/**
 * Class Permission
 * This class allows to interact with permissions security table
 *
 * @package Dotworkers\Security\Entities
 * @author  Orlando Bravo
 */
class Permission extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'permissions';

     /**
     * Relation with Roles entity
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

}