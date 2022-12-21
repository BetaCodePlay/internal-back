<?php

namespace App\Security\Collections;
use App\Security\Repositories\PermissionsDotRepo;
use App\Security\Repositories\RolesDotRepo;
use Carbon\Carbon;

/**
 * Class SecurityCollection
 *
 * Class to define the security (roles and permissions) table attributes
 *
 * @package  App\Security\Collections
 * @author Mayinis Torrealba
 */
class SecurityCollection
{
    /**
     * Format roles permissions
     * @param array $roles Roles data
     */
    public function formatRolePermissions($roles)
    {
        foreach ($roles as $role) {
            $role->permissions_data = sprintf(
                '<ul>',
            );
            foreach($role->permissions as $permission){
                $role->permissions_data .= sprintf(
                    '<li>%s</li>',
                     $permission->description,
                );
            }
            $role->permissions_data .= sprintf(
                '</ul>',
            );
            $role->actions .= sprintf(
                '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary ml-2 delete" id="delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                route('security.role-permissions.delete', [$role->id]),
                _i('Remove')
            );
        }
    }

     /**
     * Format roles users
     *@param array $users Users data
     *@param array $roles Roles data
     * 
     */
    public function formatRolesUsers($users)
    {
        foreach ($users as $user) {
            $user->userId = sprintf(
                '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                route('users.details', [$user->id]),
                $user->id
            );
            $user->roles_data = sprintf(
                '<ul>',
            );
            foreach($user->roles as $role){
                $user->roles_data .= sprintf(
                    '<li>%s</li>',
                     $role->description,
                );
            }
            $user->roles_data .= sprintf(
                '</ul>',
            );
            $user->actions = sprintf(
                '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary ml-2 delete" id="delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                route('security.manage-role-users.delete', [$user->id]),
                _i('Remove')
            );
        }
    }

     /**
     * Format roles user
     *
     * @param array $roles Roles data
     * @param int $user User ID
     */
    public function formatRoleUser($roles, $user)
    {
        foreach ($roles as $role) {
            $role->actions = sprintf(
                '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" id="delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                route('security.manage-role-data.delete', [$user, $role->id]),
                _i('Remove')
            );
        }
    }

    /**
     * Format permissions user
     *
     * @param array $permissions Permissions data
     * @param int $user User ID
     */
    public function formatPermissionUser($permissions, $user)
    {
        foreach ($permissions as $permission) {
            $permission->actions = sprintf(
                '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" id="delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                route('security.manage.permissions-data.delete', [ $user, $permission->id]),
                _i('Remove')
            );
        }
    }



    /**
     * Format permissions users
     *@param array $users Users data
     *@param array $permissions Permissions data
     * 
     */
    public function formatPermissionsUsers($users,$permissions)
    {
        foreach ($users as $user) {
            $user->userId = sprintf(
                '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                route('users.details', [$user->id]),
                $user->id
            ); 
            $user->permissionsData = sprintf(
                '<select name="users[]" class="form-control users" id="user%s" multiple="multiple" disabled>',
                $user->id
            );
            foreach($permissions as $permission){
                $selected = in_array($permission->id,$user->permissions->pluck('id')->toArray()) ? 'selected':'';
                $user->permissionsData .= sprintf(
                    '<option value="%s" %s> %s</option>',
                    $permission->id,
                    $selected,
                    _i($permission->description)
                );
            }
            $user->permissionsData .= '</select>';

            $user->actions = sprintf(
                '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary ml-2 delete" id="delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
                route('security.manage-permissions-users.delete', [$user->id]),
                _i('Remove')
            );
        }
    }

    /**
     * Format roles exclude users
     * @param array $users Users data
     * 
     */

    public function formatExcludeRoleUser ($users)
    {
        $rolesRepo = new RolesDotRepo();
        $timezone = session()->get('timezone');
        foreach ($users as $user) {
            $data = json_decode($user->data);
            $user->userId = sprintf(
                '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                route('users.details', [$user->user_id]),
                $user->user_id
            );
            if(isset($data->roles)){
                $user->roles = sprintf(
                    '<ul>',
                );
                foreach ($data->roles as $role) {
                    $rol = $rolesRepo->find($role);
                    $user->roles .= sprintf(
                        '<li>%s</li>',
                        $rol->description,
                    );
                }
                $user->roles .= sprintf(
                    '</ul>',
                );
            }else{
                $user->roles ="";
            }
            $user->date = $user->created_at->setTimezone($timezone)->format('d-m-Y H:i:s');
        }
    }

    /**
     * Format permissions exclude users
     * @param array $users Users data
     * 
     */
    public function formatExcludePermissionUser ($users)
    {
        $permissionsRepo = new PermissionsDotRepo();
        $timezone = session()->get('timezone');
        foreach ($users as $user) {
            $data = json_decode($user->data);
            $user->userId = sprintf(
                '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                route('users.details', [$user->user_id]),
                $user->user_id
            );
            if(isset( $data->permissions)){
                $user->permissions = sprintf(
                    '<ul>',
                );
                foreach ($data->permissions as $permission) {
                    $permi = $permissionsRepo->find($permission);
                    $user->permissions .= sprintf(
                        '<li>%s</li>',
                        $permi->description,
                    );
                }
                $user->permissions .= sprintf(
                    '</ul>',
                );
            }else{
                $user->permissions ="";
            }
            $user->date = $user->created_at->setTimezone($timezone)->format('d-m-Y H:i:s');
        }
    }
}
