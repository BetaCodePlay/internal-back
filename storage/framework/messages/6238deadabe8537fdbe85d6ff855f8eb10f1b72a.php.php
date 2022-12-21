<?php


namespace App\Core\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Core\Entities\Permission;

/**
 * Class Role
 *
 * This class allows to interact with roles table
 *
 * @package App\Core\Entities
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
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['description'];

    /**
     * Relation with Permission entity
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany( Permission::class);
    }

}
