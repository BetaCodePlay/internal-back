<?php


namespace App\Http\Controllers;

use App\Security\Repositories\PermissionsDotRepo;
use Dotworkers\Configurations\Configurations;
use App\Security\Repositories\RolesDotRepo;
use Dotworkers\Configurations\Enums\Codes;
use Dotworkers\Configurations\Utils;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Users\Repositories\UsersRepo;
use App\Security\Collections\SecurityCollection;
use Illuminate\Support\Collection;

/**
 * Class SecurityController
 *
 * This class allows to manage security requests
 *
 * @package App\Http\Controllers
 * @author  Mayinis Torrealba
 */
class SecurityController extends Controller
{

     /**
     * UsersRepo
     *
     * @var UsersRepo
     */
    private $usersRepo;

    /**
     * PermissionsRepo
     *
     * @var PermissionsDotRepo
     */
    private $permissionsRepo;

    /**
     * RolesRepo
     *
     * @var RolesDotRepo
     */
    private $rolesRepo;

    /**
     * SecurityCollection
     *
     * @var SecurityCollection
     */
    private $securityCollection;

    /**
     * SecurityController constructor
     *
     * @param UsersRepo $usersRepo
     * @param PermissionsDotRepo $permissionsRepo
     * @param RolesDotRepo $rolesRepo
     * @param SecurityCollection $securityCollection
     */
    public function __construct(UsersRepo $usersRepo, PermissionsDotRepo $permissionsRepo, RolesDotRepo $rolesRepo, SecurityCollection $securityCollection )
    {
        $this->usersRepo = $usersRepo;
        $this->permissionsRepo = $permissionsRepo;
        $this->rolesRepo = $rolesRepo;
        $this->securityCollection = $securityCollection;
    }

    /**
     * Show manage role permissions
     * @param Request $requests
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function manageRolePermissions(Request $request)
    {
        try {
            $permissions = $this->permissionsRepo->all();
            $roles = $this->rolesRepo->all();
            $data['roles'] = $roles;
            $data['permissions'] = $permissions;
            $data['title'] = _i('Manage role permissions');
            return view('back.security.role-permissions.manage-role-permissions', $data);
        }catch(\Exception $ex){
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * List roles -permissions
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
     public function rolePermissionsData()
    {  
        try {
            $roles = $this->rolesRepo->rolesWithPermissions();
            $this->securityCollection->formatRolePermissions($roles);
            $data = [
                'roles' => $roles,
            ];    
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }
    
    /**
     * Store role permissions
     *
     * @param Request $requests
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function storeRolePermissions(Request $request)
    {
        $this->validate($request, [
            'role' => 'required',
            'permissions' => 'required',
        ]);
        try {
            $roleId = $request->role;
            $permissionsId =  $request->permissions;
            $role = $this->rolesRepo->find($roleId);  
            foreach($permissionsId as $permissionId){
                if(in_array($permissionId,$role->permissions->pluck('id')->toArray())){
                     $data = [
                        'title' => _i('Assigned permission'),
                        'message' => _i("Permission is already assigned to this role"),
                        'close' => _i('Close')
                    ];
                    return Utils::errorResponse(Codes::$forbidden, $data);
                }
            }
            $this->rolesRepo->assignPermission($roleId, $permissionsId);    
            $data = [
                'title' => _i('Saved role'),
                'message' => _i('The role data was saved correctly'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show exclude role permissions
     * @param Request $requests
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function excludeRolePermissions(Request $request)
    {
        try {
            $roles = $this->rolesRepo->all();
            $permissions = $this->permissionsRepo->all();
            $data['roles'] = $roles;
            $data['title'] = _i('Exclude role permissions');
            return view('back.security.role-permissions.exclude-role-permissions', $data);
        }catch(\Exception $ex){
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Get permissions by role 
     *
     * @param int $role ID Roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function permissionsByRole($role)
    {
        try {
            $role = $this->rolesRepo->find($role); 
            $data = [
                'permissions' => $role->permissions,
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Exclude role permissions
     *
     * @param Request $requests
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function excludeRolePermissionsData(Request $request)
    {
        $this->validate($request, [
            'role' => 'required',
            'permissions' => 'required',
        ]);
        try {
            $roleId = $request->role;
            $permissionsId =  $request->permissions;
            foreach($permissionsId as $permissionId){
                $roles = $this->rolesRepo->removePermissionsById($roleId,$permissionId);
            }
            $data = [
                'title' => _i('Saved role'),
                'message' => _i('The role data was saved correctly'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

    /**
     * Show role-user view
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function manageRoleUsers()
    {
        try {
            $roles = $this->rolesRepo->all();
            $data['roles'] = $roles;
            $data['title'] = _i('Manage role users');
            return view('back.security.role.manage-role-users', $data);
        }catch(\Exception $ex){
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }
    
    /**
     * List roles - users
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function roleUsersData()
    {
        try {
            $currency = session('currency');
            $whitelabel = Configurations::getWhitelabel();
            $users = $this->usersRepo->usersWithRoles($whitelabel,$currency);
            $this->securityCollection->formatRolesUsers($users);
            $data = [
                'users' => $users,
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Store role-user
     * @param Request $requests
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function storeRoleUsers(Request $request)
    {
        $this->validate($request, [
            'users' => 'required',
            'roles' => 'required',
        ]);
        try {
            $users = $request->users;
            $roles =  $request->roles;
            if (!is_null($users) && !in_array(null, $users)){
                foreach($users as $userId){
                    $user = $this->rolesRepo->findExcludeRolePermissionUser($userId);
                    if (!is_null($user)){
                        $exclude = json_decode($user->data);
                        foreach($roles as $role){
                            if(isset($exclude->roles)){
                             if (($clave = array_search((string)$role, $exclude->roles)) !== false) {
                                array_splice($exclude->roles,$clave);
                             }
                            }
                        };
                        $this->rolesRepo->deleteExcludeRolePermissionUser($userId, $exclude);
                        $this->rolesRepo->assignRole($userId, $roles);
                    }
                }
            }
            $data = [
                'title' => _i('Saved role'),
                'message' => _i('The role data was saved correctly'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }

     /**
     * Manage role users (panel)
     *
     * @param Request $requests
     * @return \Symfony\Component\HttpFoundation\Response
     */
    // public function storeRole(Request $request)
    // {
    //     $this->validate($request, [
    //         'user' => 'required',
    //         'roles' => 'required',
    //     ]);
    //     try {
    //         $userId = $request->user;
    //         $rolesId =  $request->roles;
    //         $user = $this->userRepo->find($userId);  
    //         foreach($rolesId as $roleId){
    //             if(in_array($roleId,$user->roles->pluck('id')->toArray())){
    //                  $data = [
    //                     'title' => _i('Assigned role'),
    //                     'message' => _i("Role is already assigned to this user"),
    //                     'close' => _i('Close')
    //                 ];
    //                 return Utils::errorResponse(Codes::$forbidden, $data);
    //             }
    //         }
    //         $this->rolesRepo->addRoles($userId, $rolesId);
    //         $data = [
    //             'title' => _i('Saved role'),
    //             'message' => _i('The role data was saved correctly'),
    //             'close' => _i('Close')
    //         ];
    //         return Utils::successResponse($data);
    //     } catch (\Exception $ex) {
    //         \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
    //         return Utils::failedResponse();
    //     }
    // }

     /**
     * Manage permissions users (panel)
     *
     * @param Request $requests
     * @return \Symfony\Component\HttpFoundation\Response
     */
    // public function storePermissions(request $request)
    // {
    //     $this->validate($request, [
    //         'permissions' => 'required',
    //     ]);
    //     try {
    //         $permission = $request->permissions;
    //         $userId = $request->user;
    //         $this->permissionsRepo->addPermissions($userId, $permission);
    //         $data = [
    //             'title' => _i('Saved permissions'),
    //             'message' => _i('The permission data was saved correctly'),
    //             'close' => _i('Close')
    //         ];
    //         return Utils::successResponse($data);
    //     } catch (\Exception $ex) {
    //         \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
    //         return Utils::failedResponse();
    //     }
    // }

    /**
     * List roles to remover
     *
     * @param int id user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function roleDelete($user)
    {
        try {
            $roles = $this->rolesRepo->getRolesToUsers($user);
            $this->securityCollection->formatRoleUser($roles, $user);
            $data = [
                'roles' => $roles,
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Remove roles for user
     *
     * @param int id user
     *  @param string data role
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function roleDeleteData ($user,  $role)
     {
        try {
            $this->rolesRepo->removeRoleToUsers($user,$role);
            $data = [
                'title' => _i('Role removed'),
                'message' => _i(' Role was removed from user successfully'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * List permissions to remover
     *
     * @param int id user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function permissionsDelete($user)
    {
        try {
            $permissions = $this->permissionsRepo->getPermissionsToUsers($user);
            $this->securityCollection->formatPermissionUser($permissions, $user);
            $data = [
                'permissions' => $permissions,
            ];  
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Remove permissions for user
     *
     * @param int id user
     *  @param string data permission
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function permissionsDeleteData ($user,  $permission)
     {
        try {
            $this->permissionsRepo->removePermissionToUsers($user,$permission);
            $data = [
                'title' => _i('Permission removed'),
                'message' => _i(' Permission was removed from campaign successfully'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }


/**
     * List roles to user
     *
     * @param int $user ID User
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function manageRole($user)
    {
        try {
            $roles = $this->rolesRepo->getRolesToUsers($user);
            $data = [
                'roles' => $roles,
            ];  
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * List permissions to user (directs)
     *
     * @param int $user ID User
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function managePermissions($user)
    {
        try {
            $permissions = $this->permissionsRepo->getPermissionsToUsers($user);
            $data = [
                'permissions' => $permissions,
            ];           
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    // /**
    //  * List permissions to user (inherith)
    //  *
    //  * @param int $user ID User
    //  * @return \Symfony\Component\HttpFoundation\Response
    //  */
    // public function permissionsInherith($user)
    // {
    //     try {
    //         $roles =  $this->rolesRepo->rolesWithUsers($user);
    //         $permissions = $this->permissionsRepo->getPermissionsToRoles($roles);
    //         $data = [
    //             'permissions' => $permissions,
    //         ];      
    //         return Utils::successResponse($data);
    //     } catch (\Exception $ex) {
    //         \Log::error(__METHOD__, ['exception' => $ex]);
    //         return Utils::failedResponse();
    //     }
    // }

    /**
     * Remove permissions to role
     *
     * @param int $role ID Role
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function rolePermissionsDelete($role)
    {
        try {
            $this->rolesRepo->removeAllPermissions($role);
            $data = [
                'title' => _i('Permissions removed'),
                'message' => _i(' Permission was removed from campaign successfully'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }
       
    /**
     * Show permissions-user view
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function managePermissionsView()
    {
        try {
            $currency = session('currency');
            $whitelabel = Configurations::getWhitelabel();
            $permissions = $this->permissionsRepo->all();
            $users = $this->usersRepo->usersWithPermissions($whitelabel);
            $data['permissions'] = $permissions;
            $data['users'] = $users;
            $data['title'] = _i('Manage Permissions users');
            return view('back.security.permissions.manage-permissions', $data);
        }catch(\Exception $ex){
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Store permissions-user
     * @param Request $requests
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function storePermissionsUsers(Request $request)
    {
        $this->validate($request, [
            'users' => 'required',
            'permissions' => 'required',
        ]);
        try {
            $users = $request->users;
            $permissions =  $request->permissions;
            if (!is_null($users) && !in_array(null, $users)){
                foreach($users as $userId){
                    $user = $this->rolesRepo->findExcludeRolePermissionUser($userId);
                    if (!is_null($user)){
                        $exclude = json_decode($user->data);
                        foreach($permissions as $p){
                            if(isset($exclude->permissions)){
                             if (($clave = array_search((string)$p, $exclude->permissions)) !== false) {
                                array_splice($exclude->permissions,$clave);
                             }
                            }
                        };
                        $this->rolesRepo->deleteExcludeRolePermissionUser($userId, $exclude);
                        $this->permissionsRepo->assignUsers($userId, $permissions);
                    }
                }
            }
            $data = [
                'title' => _i('Saved permissions'),
                'message' => _i('The permissions data was saved correctly'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex, 'request' => $request->all()]);
            return Utils::failedResponse();
        }
    }


    /**
     * List users - permissions
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function permissionsUsersData()
    {
        try {
            $currency = session('currency');
            $whitelabel = Configurations::getWhitelabel();
            $users = $this->usersRepo->usersWithPermissions($whitelabel);
            $permissions = $this->permissionsRepo->all();
            $this->securityCollection->formatPermissionsUsers($users,$permissions);
            $data = [
                'users' => $users,
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

     /**
     * Remove roles for user
     *
     * @param int id user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function rolesUserDelete ($user)
     {
        try {
            $this->rolesRepo->deleteRelationUsers($user);
            $data = [
                'title' => _i('Role removed'),
                'message' => _i(' Role was removed from campaign successfully'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }

    }

    /**
     * Remove permissions for user
     *
     * @param int id user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function permissionsUserDelete($user)
     {
        try {
            $this->permissionsRepo->deleteRelationUsers($user);
            $data = [
                'title' => _i('Permissions removed'),
                'message' => _i(' Permissions was removed from campaign successfully'),
                'close' => _i('Close')
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

     /**
     * Show exclude role users
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function excludeRolesUsers()
    {
        try {
            $currency = session('currency');
            $whitelabel = Configurations::getWhitelabel();
            $roles = $this->rolesRepo->all();
            $data['roles'] = $roles;
            $data['currency_client'] = Configurations::getCurrenciesByWhitelabel($whitelabel);
            $data['title'] = _i('Exclude roles from user');
            return view('back.security.role.exclude-roles-users', $data);
        }catch(\Exception $ex){
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
        
    }

    /**
     * Store exclude role user
     * @param Request $requests
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function excludeRolesUsersData(Request $request)
    {
        $this->validate($request, [
            'roles' => 'required',
            'user' => 'required',
        ]);
       try {
            $userId = $request->user;
            $exclude = [];
            foreach ($request->roles as $item) {
                $this->rolesRepo->removeRoleToUsers($userId,$item);
                $exclude[] =  (string)$item;
            }
            $json = [
                'roles' => $exclude
            ];
            $excludeData = [
                'user_id' => $userId,
                'data' => json_encode($json),
                'exclude' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
            $user = $this->rolesRepo->findExcludeRolePermissionUser($userId);
            if (!is_null($user)){
                $array = json_decode($user->data);
                if(isset($array->roles)){    
                    foreach($exclude as $element){
                        $array->roles[] = $element;
                    };
                }else{
                    if(isset($array->permissions)){
                        $lastExclude = [
                            'permissions' => $array->permissions
                        ];
                        $array = array_merge($lastExclude,$json);
                    }
                }
                $this->rolesRepo->updateExcludeRolePermissionUser($userId,$array);
            }else{
                $this->rolesRepo->insertExcludeRolePermissionUser($excludeData);
            }
           $data = [
               'title' => _i('Role exclude to user'),
               'message' => _i(' Role was exclude from user successfully'),
               'close' => _i('Close')
           ];
           return Utils::successResponse($data);
       } catch (\Exception $ex) {
           \Log::error(__METHOD__, ['exception' => $ex]);
           return Utils::failedResponse();
       }
   }

    /**
     * List roles exclude to user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function excludeRolesByUsers()
    {
        try {
            $array = [];
            $whitelabel = Configurations::getWhitelabel();
            $users = $this->rolesRepo->getExcludeRolePermissionUser($whitelabel);
            if (!is_null($users)){
                foreach($users as $user){
                    $data = json_decode($user->data);
                    if(isset($data->roles)){
                        if (!empty($data->roles)) {
                            $array[] = $user;
                        }else{
                            $data = [
                                'users' => []
                            ];
                        }
                    }else{
                        $data = [
                            'users' => []
                        ];
                    }
                }
            } 
            $excludedRoles = collect($array);
            $this->securityCollection->formatExcludeRoleUser($excludedRoles);
            $data = [
                'users' => $excludedRoles
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Show exclude permissions users
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function excludePermissionsUsers()
    {
        try {
            $currency = session('currency');
            $whitelabel = Configurations::getWhitelabel();
            $permissions = $this->permissionsRepo->all();
            $data['permissions'] = $permissions;
            $data['currency_client'] = Configurations::getCurrenciesByWhitelabel($whitelabel);
            $data['title'] = _i('Exclude permissions from user');
            return view('back.security.permissions.exclude-permissions-users', $data);
        }catch(\Exception $ex){
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }

    /**
     * Store exclude permissions user
     * @param Request $requests
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function excludePermissionsUsersData(Request $request)
    {
        $this->validate($request, [
            'permissions' => 'required',
            'user' => 'required',
        ]);
       try {
            $userId = $request->user;
            $exclude = [];
            foreach ($request->permissions as $item) {
                $this->permissionsRepo->removePermissionToUsers($userId,$item);
                $exclude[] =  (string)$item;
            }
            $json = [
                'permissions' => $exclude
            ];
            $excludeData = [
                'user_id' => $userId,
                'data' => json_encode($json),
                'exclude' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
            $user = $this->rolesRepo->findExcludeRolePermissionUser($userId);
            if (!is_null($user)){
                $array = json_decode($user->data);
                if(isset($array->permissions)){    
                    foreach($exclude as $element){
                        $array->permissions[] = $element;
                    };
                }else{
                    if(isset($array->roles)){
                        $lastExclude = [
                            'roles' => $array->roles
                        ];
                        $array = array_merge($lastExclude,$json);
                    }
                }
                $this->rolesRepo->updateExcludeRolePermissionUser($userId,$array);
            }else{
                $this->rolesRepo->insertExcludeRolePermissionUser($excludeData);
            }
           $data = [
               'title' => _i('Permissions exclude to user'),
               'message' => _i('Permissions was exclude from user successfully'),
               'close' => _i('Close')
           ];
           return Utils::successResponse($data);
       } catch (\Exception $ex) {
           \Log::error(__METHOD__, ['exception' => $ex]);
           return Utils::failedResponse();
       }
   }

   /**
     * List permissions exclude to user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function excludePermissionsByUsers()
    {
        try {
            $array = [];
            $whitelabel = Configurations::getWhitelabel();
            $users = $this->rolesRepo->getExcludeRolePermissionUser($whitelabel);
            if (!is_null($users)) {
                foreach($users as $user){
                    $data = json_decode($user->data);
                    if(isset($data->permissions)){
                        if(!empty($data->permissions)){
                            $array[] = $user;
                        }else{
                            $data = [
                                'users' => []
                            ];
                        }
                    }else{
                        $data = [
                            'users' => []
                        ];
                    }
                }
            }
            $excludedPermissions = collect($array);
            $this->securityCollection->formatExcludePermissionUser($excludedPermissions);
            $data = [
                'users' => $excludedPermissions
            ];
            return Utils::successResponse($data);
        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            abort(500);
        }
    }
}