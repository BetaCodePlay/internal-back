<?php


namespace App\Core\Repositories;

use App\Core\Entities\Role;

/**
 * Class RolesRepo
 *
 * This class allows to interact with roles security table
 *
 * @package App\Core\Repositories
 * @author  Orlando Bravo
 */
class RolesRepo
{
    /**
     * Get all role
     *
     * @return mixed
     */
    public function All()
    {
        $role = Role::orderBy('description', 'ASC')
            ->get();
        return $role;
    }

    /**
     * Get role
     *
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        $role = Role::where('id', $id)
            ->first();
        return $role;
    }

    /**
     * Store role
     *
     * @param $data
     * @return mixed
     */
    public function store($data)
    {
        $role = new Role();
        $role->fill($data);
        $role->save();
        return $role;
    }

    /**
     * Update role
     *
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data)
    {
        $role = Role::find($id);
        $role->fill($data);
        $role->save();
        return $role;
    }
}
