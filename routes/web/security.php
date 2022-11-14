<?php

/**
 * Security routes
 */
Route::group(['prefix' => 'security', 'middleware' => ['auth']], function () {

    // Manage role permissions view
    Route::get('role-permissions', [
        'as' => 'security.manage-role-permissions',
        'uses' => 'SecurityController@manageRolePermissions'
    ]);

    // Store role permissions
    Route::post('store-role-permissions', [
        'as' => 'security.store-role-permissions',
        'uses' => 'SecurityController@storeRolePermissions'
    ]);
    
    // List relation roles - permissions
    Route::get('role-permissions-data', [
        'as' => 'security.role-permissions-data',
        'uses' => 'SecurityController@rolePermissionsData'
    ]);

    // Remover permission to roles
     Route::get('role-permissions-delete/{role}', [
        'as' => 'security.role-permissions.delete',
        'uses' => 'SecurityController@rolePermissionsDelete',
    ]);
 
    //Exclude role permissions view 
    Route::get('exclude-role-permissions', [
        'as' => 'security.exclude-role-permissions',
        'uses' => 'SecurityController@excludeRolePermissions'
    ]);

    //Permissions by role (List for view exclude role permissions)
    Route::get('permissions-by-role/{role?}', [
        'as' => 'security.permissions-by-role',
        'uses' => 'SecurityController@permissionsByRole'
    ]);

    //Exclude role permission
    Route::post('exclude-role-permissions-data', [
        'as' => 'security.exclude-role-permissions-data',
        'uses' => 'SecurityController@excludeRolePermissionsData'
    ]);

    // Manage role users view
    Route::get('role-users', [
        'as' => 'security.role-users',
        'uses' => 'SecurityController@manageRoleUsers'
    ]);

    // Store relation roles - users
    Route::post('store-role-users', [
        'as' => 'security.manage-store-role-users',
        'uses' => 'SecurityController@storeRoleUsers'
    ]);

    // List relation role - users
    Route::get('role-users-data', [
        'as' => 'security.role-users-data',
        'uses' => 'SecurityController@roleUsersData'
    ]);

    // List relation roles - users
    Route::get('manage/role/{user?}', [
        'as' => 'security.manage-role',
        'uses' => 'SecurityController@manageRole'
    ]);

    // // Store relation roles - users
    // Route::post('manage/store-role', [
    //     'as' => 'security.manage-store-role',
    //     'uses' => 'SecurityController@storeRole'
    // ]);

    // Remover role to user
    Route::get('manage/role-users-delete/{user}', [
            'as' => 'security.manage-role-users.delete',
            'uses' => 'SecurityController@rolesUserDelete',
    ]);
    
    
    // Remover role list
     Route::get('manage/role-delete/{user}', [
         'as' => 'security.manage-role.delete',
         'uses' => 'SecurityController@roleDelete',
     ]);

    // Remover role
    Route::get('manage/role-data-delete/{user}/{role}', [
        'as' => 'security.manage-role-data.delete',
        'uses' => 'SecurityController@roleDeleteData',
    ]);

     // View relation permission - users
     Route::get('permissions/assing', [
        'as' => 'security.manage-permissions-view',
        'uses' => 'SecurityController@managePermissionsView'
    ]);

    // Store relation permissions - users
    Route::post('manage/store-permissions-users', [
        'as' => 'security.manage-store-permissions-users',
        'uses' => 'SecurityController@storePermissionsUsers'
    ]);

    // Table relation permissions - users
    Route::get('manage/permissions-users-data', [
        'as' => 'security.manage-permissions-users-data',
        'uses' => 'SecurityController@permissionsUsersData'
    ]);


    // // Store relation permissions - users
    // Route::post('manage/store-permissions', [
    //     'as' => 'security.manage-store-permissions',
    //     'uses' => 'SecurityController@storePermissions'
    // ]);

    // Remover permission to user
        Route::get('manage/permissions-users-delete/{user}', [
            'as' => 'security.manage-permissions-users.delete',
            'uses' => 'SecurityController@permissionsUserDelete',
    ]);

    // Remover permission list
     Route::get('manage/permissions-delete/{user}', [
         'as' => 'security.manage-permissions.delete',
         'uses' => 'SecurityController@permissionsDelete',
     ]);
    
     // Remover permission
     Route::get('manage/permissions-data-delete/{user}/{permission}', [
        'as' => 'security.manage.permissions-data.delete',
        'uses' => 'SecurityController@permissionsDeleteData',
   ]);

    // List relation permission - users
    // Route::get('manage/permissions-inherith/{user}', [
    //     'as' => 'security.manage-permissions-inherith',
    //     'uses' => 'SecurityController@permissionsInherith'
    // ]);

    // List relation permission - users
    Route::get('manage/permissions/{user?}', [
        'as' => 'security.manage-permissions',
        'uses' => 'SecurityController@managePermissions'
    ]);

    //exclude role-users view

    Route::get('role/exclude-roles-users', [
        'as' => 'security.exclude-roles-users',
        'uses' => 'SecurityController@excludeRolesUsers'
    ]);

    //exclude role-users data

    Route::post('role/exclude-roles-users-data', [
        'as' => 'security.exclude-roles-users-data',
        'uses' => 'SecurityController@excludeRolesUsersData'
    ]);

    //exclude role-users table

    Route::get('role/exclude-roles-users-list', [
        'as' => 'security.exclude-roles-users-list',
        'uses' => 'SecurityController@excludeRolesByUsers'
    ]);

        
    //exclude permissions-users view

    Route::get('permissions/exclude-permissions-users', [
        'as' => 'security.exclude-permissions-users',
        'uses' => 'SecurityController@excludePermissionsUsers'
    ]);


     //exclude permissions-users data

     Route::post('permissions/exclude-permissions-users-data', [
        'as' => 'security.exclude-permissions-users-data',
        'uses' => 'SecurityController@excludePermissionsUsersData'
    ]);

    //exclude permissions-users table

    Route::get('permissions/exclude-permissions-users-list', [
        'as' => 'security.exclude-permissions-users-list',
        'uses' => 'SecurityController@excludePermissionsByUsers'
    ]);
    
    
});
