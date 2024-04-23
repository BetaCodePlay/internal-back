<?php

namespace Dotworkers\Security;

use Dotworkers\Security\Repositories\PermissionsRepo;
use Dotworkers\Security\Repositories\RolesRepo;
use Dotworkers\Security\Repositories\PermissionsDotRepo;
use Dotworkers\Security\Repositories\RolesDotRepo;
/**
 * Class Security
 *
 * This class allows to interact with security security
 *
 * @package Dotworkers\Security
 * @author  Orlando Bravo
 */
class Security
{
     /**
     * Assign Role by security
     *
     * @param int $user User ID
     * @param int $role Role ID
     * @return mixed
     */
    public static function assignRole($user, $role)
    {
        $rolesRepo  = new RolesRepo();
        $roleData = [
            'role_id' => $role,
            'user_id' => $user
        ];
        $assignRole = $rolesRepo->assignRole($roleData);
        return $assignRole;
    }

    /**
     * Check user permissions
     *
     * @param int $permission Permission
     * @param array $userPermissions User permissions
     * @return bool
     */
    public static function checkPermissions($permission, $userPermissions)
    {
        return in_array($permission, $userPermissions);
    }

     /**
     * Get user permission by security
     *
     * @param int $user user ID
     * @return mixed
     */
    public static function getUserPermissions($user)
    {
        $rolesRepo = new RolesRepo();
        $permissionsRepo = new PermissionsRepo();
        $permissions = collect();
        $rolePermissions = $rolesRepo->getPermissionsByUser($user);
        $additionalPermissions = $permissionsRepo->getPermissionsByUser($user);

        foreach ($rolePermissions as $rolePermission) {
            $permissions->push($rolePermission->id);
        }

        foreach ($additionalPermissions as $additionalPermission) {
            $permissions->push($additionalPermission->id);
        }
        $uniquePermissions = $permissions->unique()->values()->all();
        return $uniquePermissions;
    }

    /**
     * Get user roles
     *
     * @param int $user User ID
     * @return mixed
     */
    public static function getUserRoles($user)
    {
        $rolesRepo = new RolesRepo();
        $roles = collect();
        $userRoles = $rolesRepo->getRolesByUser($user);

        foreach ($userRoles as $userRole) {
            $roles->push($userRole->id);
        }
        return $roles->unique()->values()->all();
    }

     /**
     * Assign Role Dot by security 
     *
     * @param int $user User ID
     * @param int $role Role ID
     * @return mixed
     */
    public static function assignRoleDot($user, $role)
    {
        $rolesRepo  = new RolesDotRepo();
        $assignRole = $rolesRepo->assignRole($user,$role);
        return $assignRole;
    }

    /**
     * Get user permission dot by security
     *
     * @param int $user user ID
     * @return mixed
     */
    public static function getUserPermissionsDot($user)
    {
        $rolesRepo = new RolesDotRepo();
        $permissionsRepo = new PermissionsDotRepo();
        $permissions = collect();
        $rolePermissions = $rolesRepo->getPermissionsByUser($user);
        $additionalPermissions = $permissionsRepo->getPermissionsByUser($user);

        foreach ($rolePermissions as $rolePermission) {
            $permissions->push($rolePermission->id);
        }

        foreach ($additionalPermissions as $additionalPermission) {
            $permissions->push($additionalPermission->id);
        }
        $uniquePermissions = $permissions->unique()->values()->all();
        return $uniquePermissions;
    }

     /**
     * Get user roles dot
     *
     * @param int $user User ID
     * @return mixed
     */
    public static function getUserRolesDot($user)
    {
        $rolesRepo = new RolesDotRepo();
        $roles = collect();
        $userRoles = $rolesRepo->getRolesByUser($user);

        foreach ($userRoles as $userRole) {
            $roles->push($userRole->id);
        }
        return $roles->unique()->values()->all();
    }
}
