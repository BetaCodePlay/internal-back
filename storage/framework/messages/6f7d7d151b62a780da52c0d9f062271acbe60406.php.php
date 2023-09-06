<?php


namespace App\Security\Repositories;

use Dotworkers\Security\Entities\Role;
use Illuminate\Support\Facades\DB;

/**
 * Class RolesDotRepo
 *
 * This class allows to interact with roles Entities
 *
 * @package Dotworkers\Security
 * @author  Estarly Olivar
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
        $role = DB::table('role_user')
            ->insert($data);
        return $role;
    }

    /**
     * Delete role user
     *
     * @param array $data Role user data
     * @param int $role Role ID
     */
    public function deleteRoles($data)
    {
        $role = DB::table('role_user')->where($data)->delete();

        return $role;
    }

    /**
     * Get all roles
     *
     * @return mixed
     */
    public function all()
    {
        $roles = Role::orderBy('description', 'ASC')
        ->get();
        return $roles;
    }

    /**
     * find rol
     *
     * @return mixed
     */
    public function findRolUser($user,$rol)
    {
        $rol = DB::table('role_user')->where(['user_id'=>$user,'role_id'=> $rol])->first();
        return $rol;
    }

    /**
     * Get all role
     * @param int $user ID User
     * @return mixed
     */
    public function getRolesUser($user)
    {
        $roles = Role::select(['roles.id','roles.description'])
            ->join('role_user', 'roles.id', '=', 'role_user.role_id')
            ->where('role_user.user_id', $user)
            ->get();

        return $roles;
    }

}
