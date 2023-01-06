<?php

namespace Dotworkers\Security\Repositories;

use Dotworkers\Security\Entities\Permission;

/**
 * Class PermissionsRepo
 *
 * This class allows to interact with permissions security table
 *
 * @package Dotworkers\Security\Repositories
 * @author  Orlando Bravo
 */
class PermissionsRepo
{
     /**
     *  get permissions by user
     *
     * @param int $user User ID
     * @return mixed
     */
    public function getPermissionsByUser($user)
    {
        $permission = Permission::select('permissions.id')
            ->join('permission_user', 'permissions.id', '=', 'permission_user.permission_id')
            ->where('permission_user.user_id', $user)
            ->get();
        return $permission;
    }
}