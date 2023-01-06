<?php


namespace App\Core\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Core\Entities\Role;

/**
 * Class Permission
 *
 * This class allows to interact with permissions table
 *
 * @package App\Core\Entities
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
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['description', 'depends'];

    /**
     * Relation with Roles entity
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany( Role::class);
    }
}
