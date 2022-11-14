<?php


namespace App\Core\Repositories;

use App\Core\Entities\Permission;

/**
 * Class PermissionsRepo
 *
 * This class allows to interact with permissions Entities
 *
 * @package App\Core\Repositories
 * @author  Orlando Bravo
 */
class PermissionsRepo
{
    /**
     * Get all permission
     *
     * @return mixed
     */
    public function All()
    {
        $permission = Permission::orderBy('description', 'ASC')
            ->get();
        return $permission;
    }


    /**
     * Assign permission
     *
     * @param array $data permission role data
     * @param int $permission Permission ID
     */
    public function assignPermission($data)
    {
        $permission = \DB::table('permission_role')
            ->insert($data);
        return $permission;
    }

    /**
     * Get permission
     *
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        $permission = Permission::where('id', $id)
            ->first();
        return $permission;
    }

    /**
     * Search permission
     *
     * @param int $permission Permission tupe ID
     * @param int $role Role type ID
     * @return mixed
     */
    public function searchByPermission($role, $permission)
    {
        $credentials = \DB::table('permission_role')::where('permission_id', $permission)
            ->where('role_id', $role)
            ->first();
        return $credentials;
    }

    /**
     * Store permission
     *
     * @param $data
     * @return mixed
     */
    public function store($data)
    {
        $permission = new Permission();
        $permission->fill($data);
        $permission->save();
        return $permission;
    }

    /**
     * Update permission
     *
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data)
    {
        $permission = Permission::find($id);
        $permission->fill($data);
        $permission->save();
        return $permission;
    }
}
