<?php


namespace App\Security\Repositories;

use App\Security\Entities\RoleDot;
use App\Users\Entities\User;
use App\Security\Entities\ExcludeRolePermissionsUser;
/**
 * Class RolesDotRepo
 *
 * This class allows to interact with roles Entities
 *
 * @package App\Security\Repositories
 * @author  Mayinis Torrealba
 */
class RolesDotRepo
{

    /**
     * Get all role
     *
     * @return mixed
     */
    public function All()
    {
        $role = RoleDot::orderBy('description', 'ASC')
        ->get();
        return $role;
    }

     /**
     * Assign permissions to roles
     * @param int $id ID Role
     * @param int $permission Permission Data
     * @return mixed
     */

    public function assignPermission($id, $permissions)
    {
        $role = RoleDot::findById($id);
        $role->permissions()->attach($permissions);
        $role->save();
        return $role;
    } 

    /**
     * Get roles without assigned permissions 
     *
     * @return mixed
     */
    public function rolesWithoutPermissions()
    {
        $role = RoleDot::doesntHave('permissions')
        ->orderBy('description','ASC')->get();
        return $role;
    }

     /**
     * Get roles assigned users 
     * @param int $id ID User
     * @return mixed
     */
    public function rolesWithUsers($id)
    {    
        $roles = RoleDot::select('id')->whereHas("users", function($query) use ($id) {
             $query->where("model_id", $id);
        })->get();
        return $roles;
    }

    /**
     * Get roles without assigned users 
     * @param int $id ID User
     * @return mixed
     */
    public function rolesWithoutUserSpecific($id)
    {
        $role = RoleDot::whereDoesntHave('users', function ($query) use ($id) { 
            $query->where('model_id', $id);
        })->get();
        return $role;
    }

    /**
     * Get roles with permissions 
     *
     * @return mixed
     */
    public function rolesWithPermissions()
    {
        $role = RoleDot::has('permissions')
        ->orderBy('description','ASC')->get();
        return $role;
    }
      
    /**
     * Set Role to User
     *
     * @param int $id ID User
     * @param array $role Data Role
     * @return mixed
     */  

    public function assignRole($id , array $role  = null)
    {
        $user = User::find($id);
        $user->assignRole($role);
        $user->save();
        return $user;
    }

    /**
     * Get list Role to User
     *
     * @param int $id ID User
     * @return mixed
     */  
    public function getRolesToUsers($id)
    {
        $user = User::find($id);
        $role = $user->roles;
        return $role;
    }
    
    /**
     * Remove Role to user
     *
     * @param int $id ID User
     * @param string $role data Role
     * @return mixed
     */  
    public function removeRoleToUsers($id,$role)
    {
        $user = User::find($id);
        $user->removeRole($role);
        $user->save();
        return $user;
    }

    /**
     * Find Role
     *
     * @param int $id ID Role
     * @return mixed
     */ 
    public function find($id)
    {
        $role = RoleDot::find($id);
        return $role;
    }
   
    /**
     * Remove Permission to Role
     *
     * @param int $id ID Role
     * @return mixed
     */  
    public function removeAllPermissions($id)
    {
        $role = RoleDot::find($id);
        $role->permissions()->detach();
        $role->save();
        return $role;
    }

    /**
     * Remove role permissions by id  
     * @param int $id ID User
     * @return mixed
     */   
    public function removePermissionsById($id,$permission)
    {
        $role = RoleDot::find($id);
        $role->revokePermissionTo($permission);
        $role->save();
        return $role;
    }

     /**
     * Remove Roles to Users 
     * @param int $id ID User
     * @return mixed
     */   
    public function deleteRelationUsers($id)
    {
        $user = User::find($id);
        $user->syncRoles([]);
        return $user;
    }

    /**
     * Get users roles exclude
     *
     * @param int $id id user
     * @return mixed
     */
    public function findExcludeRolePermissionUser($id)
    {
        $user = ExcludeRolePermissionsUser::where('user_id', $id)->first();
        return $user;
    }

    /**
     * Update users roles exclude
     * @param int $id id user
     * @param array $data Exclude
     * @return mixed
     */

    public function updateExcludeRolePermissionUser($id,$data)
    {
        $user = ExcludeRolePermissionsUser::where('user_id', $id)->update([
            'data' => json_encode($data)
        ]);
        return $user;
    }

    /**
     * Insert users roles exclude
     * @param array $data Exclude
     * @return mixed
     */
    public function insertExcludeRolePermissionUser($data)
    {
        $user = ExcludeRolePermissionsUser::insert($data);
        return $user;
    }

    /**
     * Delete exclude roles user
     * @param int $id id user
     * @param array $data data user
     * @return mixed
     */
    public function deleteExcludeRolePermissionUser($id,$data)
    {
        $user = ExcludeRolePermissionsUser::where('user_id', $id)->update([
            'data' => json_encode($data)
        ]);
        return $user;
    }

    /**
     * Get exclude role user
     *
     * @param int $whitelabel Whitelabel ID
     * @return mixed
     */
    public function getExcludeRolePermissionUser($whitelabel)
    {
        $users = User::select('users.username', 'exclude_role_permissions_user.*')
        ->join('exclude_role_permissions_user', 'exclude_role_permissions_user.user_id', '=', 'users.id')
        ->where('users.whitelabel_id', $whitelabel)
        ->orderBy('users.username', 'DESC')
        ->get();
        return $users;
    }
    
    /**
     *find exclude roles user
     *
     * @param array $data data user
     * @return mixed
     */
    public function findExcludeRoleUser($id)
    {
        $user = ExcludeRolePermissionsUser::find($id);
        return $user;
    }
}
