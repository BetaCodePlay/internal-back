<?php

namespace Dotworkers\Security\Repositories;

use Dotworkers\Security\Entities\PermissionDot;

/**
 * Class PermissionsDotRepo
 *
 * This class allows to interact with permissions security table
 *
 * @package Dotworkers\Security\Repositories
 * @author  Mayinis Torrealba
 */
class PermissionsDotRepo
{
     /**
     *  get permissions by user
     *
     * @param int $user User ID
     * @return mixed
     */
    public function getPermissionsByUser($user)
    {
        $permission = PermissionDot::select('permissions_dot.id')
            ->join('user_has_permissions', 'permissions_dot.id', '=', 'user_has_permissions.permission_id')
            ->where('user_has_permissions.model_id', $user)
            ->get();
        return $permission;
    }
}