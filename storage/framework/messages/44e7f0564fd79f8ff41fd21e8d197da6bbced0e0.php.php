<?php

namespace Dotworkers\Security\Entities;

use Illuminate\Database\Eloquent\Model;
use Dotworkers\Security\Entities\Permission;

/**
 * Class Role
 * This class allows to interact with roles security table
 *
 * @package Dotworkers\Security\Entities
 * @author  Orlando Bravo
 */
class Role extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'roles';


    /**
     * Relation with Permission entity
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

}
