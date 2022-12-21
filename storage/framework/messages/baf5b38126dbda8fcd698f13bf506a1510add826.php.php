<?php


namespace App\Security\Repositories;

use App\Security\Entities\PermissionDot;
use App\Security\Entities\RoleDot;
use App\Users\Entities\User;

/**
 * Class PermissionsDotRepo
 *
 * This class allows to interact with permissions Entities
 *
 * @package App\Security\Repositories
 * @author  Mayinis Torrealba
 */
class PermissionsDotRepo
{

    /**
     * Get all permission
     *
     * @return mixed
     */
    public function All()
    {
        $permission = PermissionDot::orderBy('description', 'ASC')
        ->get();
        return $permission;
    }

     /**
     * Get permissions availables users 
     * @param int $id ID User
     * @return mixed
     */
    public function permissionsWithoutUsers($id){
        $permissions = PermissionDot::select('permissions_dot.*')
        ->leftJoin('role_has_permissions', 'permissions_dot.id', '=', 'role_has_permissions.permission_id')
        ->whereNull('role_has_permissions.permission_id')
        ->whereDoesntHave('users', function ($query) use ($id) {
            $query->where('model_id', $id );
        })
        ->get();
        return $permissions;
    }

    /**
     * Set Permission to User
     *
     * @param int $id ID User
     * @param array $permission Data Permissions
     * @return mixed
     */  
    public function addPermissions($id , array $permission  = null){
        $user = User::find($id);
        $user->givePermissionTo($permission);
        $user->save();
        return $user;
    }

    /**
     * Remove  Permission  to user
     *
     * @param int $id ID User
     * @param string data Permission
     * @return mixed
     */  

    public function removePermissionToUsers($id,$permission){
        $user = User::find($id);
        $user->revokePermissionTo($permission);
        $user->save();
        return $user;
    }

    /**
     * Get list Direct Permissions to User
     *
     * @param int $id ID User
     * @return mixed
     */  
    public function getPermissionsToUsers($id){
        $user = User::find($id);
        $permissions = $user->getDirectPermissions();
        return $permissions;
    }

    /**
     * Get list Permission to User
     *
     * @param array $roles Data Roles
     * @return mixed
     */  
    // public function getPermissionsToRoles($roles){
    //    $permissions = PermissionDot::select('permissions_dot.*')
    //    ->distinct()
    //    ->join('role_has_permissions', 'permissions_dot.id', '=', 'role_has_permissions.permission_id')
    //    ->whereIn('role_has_permissions.role_id', $roles)
    //    ->get();
    //    return $permissions;
    // }

    /**
     * Remove Permission to User
     *
     * @param int $id ID User
     * @return mixed
     */   
    public function deleteRelationUsers($id){
        $user = User::find($id);
        $user->syncPermissions([]);
        return $user;
    }

    /**
     * Assig Permissions to User
     *
     * @param array $id id User
     *  @param array $permissions Data Permissions
     * @return mixed
     */ 
    public function assignUsers($id, array $permissions){
        $user = User::find($id);
        $user->givePermissionTo($permissions);
        return $user;
    }

    /** 
     * Find permission
     *
     * @param int $id ID Permission
     * @return mixed
     */ 
    public function find($id)
    {
        $permission = PermissionDot::find($id);
        return $permission;
    }    
}
