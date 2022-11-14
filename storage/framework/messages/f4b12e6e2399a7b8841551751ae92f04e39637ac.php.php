<?php

namespace Dotworkers\Security\Repositories;

use Dotworkers\Security\Entities\RoleDot;
use Dotworkers\Security\Entities\User;

/**
 * Class RolesDotRepo
 *
 * This class allows to interact with roles security table
 *
 * @package Dotworkers\Security\Repositories
 * @author  Mayinis Torrealba
 */
class RolesDotRepo
{

    /**
     * Assign role
     *
     * @param array $data Role user data
     * @param int $role Role ID
     */
    public function assignRole($id, $role)
    {
        $user = User::find($id);
        $user->roles()->attach($role);
        $user->save();
        return $user;
    }

    /**
     *  Get permissions by user
     *
     * @param int $user User ID
     * @return mixed
     */
    public function getPermissionsByUser($user)
    {
        $permissions = RoleDot::select('permissions_dot.id')
            ->join('user_has_roles', 'roles_dot.id', '=', 'user_has_roles.role_id')
            ->join('role_has_permissions', 'user_has_roles.role_id', '=', 'role_has_permissions.role_id')
            ->join('permissions_dot', 'role_has_permissions.permission_id', '=', 'permissions_dot.id')
            ->where('user_has_roles.model_id', $user)
            ->groupBy('permissions_dot.id')
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
        $roles = RoleDot::select('roles_dot.id')
            ->join('user_has_roles', 'roles_dot.id', '=', 'user_has_roles.role_id')
            ->where('user_has_roles.model_id', $user)
            ->get();
        return $roles;
    }
}

