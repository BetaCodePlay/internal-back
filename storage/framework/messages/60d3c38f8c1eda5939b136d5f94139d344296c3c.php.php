<?php

namespace Dotworkers\Security\Repositories;

use Dotworkers\Security\Entities\Role;

/**
 * Class RolesRepo
 *
 * This class allows to interact with roles security table
 *
 * @package Dotworkers\Security\Repositories
 * @author  Orlando Bravo
 */
class RolesRepo
{

    /**
     * Assign role
     *
     * @param array $data Role user data
     * @param int $role Role ID
     */
    public function assignRole($data)
    {
        $role = \DB::table('role_user')
            ->insert($data);
        return $role;
    }

    /**
     * Delete assign role
     *
     * @param int $id user ID
     * @return mixed
     */
    public function deleteAssignRole($id)
    {
        $role =\DB::table('role_user')
            ->where('user_id', $id)
            ->delete();
        return $role;
    }

    /**
     *  Get permissions by user
     *
     * @param int $user User ID
     * @return mixed
     */
    public function getPermissionsByUser($user)
    {
        $permissions = Role::select('permissions.id')
            ->join('role_user', 'roles.id', '=', 'role_user.role_id')
            ->join('permission_role', 'role_user.role_id', '=', 'permission_role.role_id')
            ->join('permissions', 'permission_role.permission_id', '=', 'permissions.id')
            ->where('role_user.user_id', $user)
            ->groupBy('permissions.id')
            ->get();
        return $permissions;
    }

    /**
     * Get roles by user
     *
     * @param int $user User ID
     * @return mixed
     */
    public function getRolesByUser($user)
    {
        $roles = Role::select('roles.id')
            ->join('role_user', 'roles.id', '=', 'role_user.role_id')
            ->where('role_user.user_id', $user)
            ->get();
        return $roles;
    }
}

