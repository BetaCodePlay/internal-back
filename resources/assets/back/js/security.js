import {clearForm, initSelect2} from "./commons";
import {swalError, swalSuccessWithButton, swalConfirm} from "../../commons/js/core";

class Security {

     // Assign permission to role
    assignPermissions() {
        let $button = $('#save');
        let $form = $('#save-form');
        clearForm($form);
        $button.click(function () {
            $button.button('loading');
            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                data: $form.serialize()
            }).done(function (json) {
                swalSuccessWithButton(json);
                clearForm($form);
                setTimeout(() => {
                    location.reload();
                }, 500)
            }).fail(function (json) {
                swalError(json);
            }).always(function () {
                $button.button('reset');
            });
        });
    }
    // Initialize table role permissions
    rolePermissions(){
        initSelect2();
        let $table = $('#role-permissions-table');
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.roles"
            },
            "order": [],
            "columns": [
                {"data": "id"},
                {"data": "description"},
                {"data": "permissions_data"},
                {"data":"actions", "className": "text-right"},
            ],
            "initComplete": function () {
                $(document).on('click', '.delete', function () {
                    let $button = $(this);
                    swalConfirm($button.data('route'), function () {
                        $table.DataTable().ajax.url($table.data('route')).load();
                    });
                });
            },
            "drawCallback": function() {
                initSelect2();
            }
        });
        $table.on('xhr.dt', function (event, settings, json, xhr) {
            if (xhr.status === 500 || xhr.status === 422) {
                swalError(xhr);
            }
        });
    }
    // Update permissions to role
    updateRolePermissions(){
        initSelect2();
        let $button = $('#update');
        let $form = $('#role-permissions-form');
        let $modal = $('#update-role-permissions-modal');
        $button.click(function () {
            $button.button('loading');
            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                data: $form.serialize()
            }).done(function (json) {
                $form.trigger('reset');
                $('select').val(null).trigger('change');
                $('#update-role-permissions-modal').modal('hide');
                setTimeout(() => {
                    location.reload();
                }, 500)
                swalSuccessWithButton(json);
            }).fail(function (json) {
                swalError(json);
            }).always(function () {
                $button.button('reset');
            });
        });
        $modal.on('show.bs.modal', function (event) {
            let select;
            let option;
            let $target = $(event.relatedTarget);
            select = document.getElementById("role_selected");
            option = document.createElement("option");
            option.value = $target.data('role');
            option.text = $target.data('description');;
            select.appendChild(option);
        })
    }
    //Initialize select permissions by role
    permissionsByRole(placeholder){
        let $role = $('#role');
        let $permission = $('#permissions');
        $permission.select2({
            placeholder: placeholder
        });
        $role.on('change', function() {
            $.ajax({
                url:`${$permission.data('route')}/${$role.val()}`,
                type:'GET',
                success:function(data) {
                    $permission.empty();
                    console.log(data.data.permissions);
                    $.each(data.data.permissions, function(key,value) {
                        console.log(value.description);
                        $permission.append($("<option></option>").attr("value", value.id).text(value.description));
                    });
                }
            });
        });
    };
    //Exclude Role Permissions
    excludeRolePermissions(){
        let $button = $('#save');
        let $form = $('#exclude-role-permissions-form');

        $button.click(function () {
            $button.button('loading');
            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                data: $form.serialize()
            }).done(function (json) {
                $form.trigger('reset');
                setTimeout(() => {
                    location.reload();
                }, 500)
                swalSuccessWithButton(json);
            }).fail(function (json) {
                swalError(json);
            }).always(function () {
                $button.button('reset');
            });
        });
    }
    // Initialize table role users
    rolesUsers(){
        let $table = $('#roles-users-table');
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.users"
            },
            "order": [],
            "columns": [
                {"data": "userId"},
                {"data": "username"},
                {"data": "roles_data"},
                {"data":"actions", "className": "text-right"},
            ],
            "initComplete": function () {
                let api = this.api();
                api.buttons().container()
                    .appendTo($('#manage-role-table-buttons')); 
           
                $(document).on('click', '.delete', function () {
                    let $button = $(this);
                    swalConfirm($button.data('route'), function () {
                        $table.DataTable().ajax.url($table.data('route')).load();
                    });
                });
            },
            "drawCallback": function() {
                $('.users').select2({
                    width : '100%'
                });
            }
        });
        $table.on('xhr.dt', function (event, settings, json, xhr) {
            if (xhr.status === 500 || xhr.status === 422) {
                swalError(xhr);
            }
        });
    }

         // Add roles 
         addRoles() {
            let $button = $('#role-user');
            let $form = $('#role-user-form');
    
            $button.click(function () {
                $button.button('loading');
    
                $.ajax({
                    url: $form.attr('action'),
                    method: 'post',
                    data: $form.serialize()
    
                }).done(function (json) {
                    $form.trigger('reset');
    
                    $('select').val(null).trigger('change');
                    $('#role-user-modal').modal('hide');
                    setTimeout(() => {
                        location.reload();
                    }, 500)
                    swalSuccessWithButton(json);
    
                }).fail(function (json) {
                    swalError(json);
    
                }).always(function () {
                    $button.button('reset');
                });
            });
        }
    
        //add permissions
        addPermissions(){
            let $button = $('#permission-user');
            let $form = $('#permission-user-form');
    
            $button.click(function () {
                $button.button('loading');
    
                $.ajax({
                    url: $form.attr('action'),
                    method: 'post',
                    data: $form.serialize()
    
                }).done(function (json) {
                    $form.trigger('reset');
    
                    $('select').val(null).trigger('change');
                    $('#permission-user-modal').modal('hide');
                    setTimeout(() => {
                        location.reload();
                    }, 500)
                    swalSuccessWithButton(json);
    
                }).fail(function (json) {
                    swalError(json);
    
                }).always(function () {
                    $button.button('reset');
                });
            });
    
        }
    
    //remove Roles List
        removeRolesList(){
            let $table = $('#roles-remove-table');
            $table.DataTable({
                    "ajax": {
                        "url": $table.data('route'),
                        "dataSrc": "data.roles"
                    },
                    "order": [],
                    "columns": [
                        {"data": "id"},
                        {"data": "description"},
                        {"data":"actions", "className": "text-right"},
                    ],
                    "initComplete": function () {
                        let api = this.api();
                        api.buttons().container()
                            .appendTo($('#table-buttons'));
                        $(document).on('click', '.delete', function () {
                            let $button = $(this);
                            swalConfirm($button.data('route'), function () {
                                $table.DataTable().ajax.url($table.data('route')).load();
                            });
                        });
                    }
                });
        }
    
        // remove Permissions List
        removePermissionList(){
            let $table = $('#permissions-remove-table');
            $table.DataTable({
                    "ajax": {
                        "url": $table.data('route'),
                        "dataSrc": "data.permissions"
                    },
                    "order": [],
                    "columns": [
                        {"data": "id"},
                        {"data": "description"},
                        {"data":"actions", "className": "text-right"},
                    ],
                    "initComplete": function () {
                        let api = this.api();
                        api.buttons().container()
                            .appendTo($('#table-buttons'));
                        $(document).on('click', '.delete', function () {
                            let $button = $(this);
                            swalConfirm($button.data('route'), function () {
                                $table.DataTable().ajax.url($table.data('route')).load();
                            });
                        });
                    }
                });
        }
    
        // List Roles Users
        listRolesUsers() {
            let $table = $('#roles-user-table');
            let $button = $('#roles-user-update');
            let api;
    
            $table.DataTable({
                "ajax": {
                    "url": $table.data('route'),
                    "dataSrc": "data.roles"
                },
                "order": [],
                "columns": [
                    {"data": "id"},
                    {"data": "description"}
                ],
                "initComplete": function () {
                    api = this.api()
                    api.buttons().container()
                        .appendTo($('#roles-user-table-buttons'));
                }
            });
    
            $button.click(function () {
                $button.button('loading');
                $table.on('draw.dt', function () {
                    $button.button('reset');
                });
            });
        }
    
        // List Permissions Inherith Users
        listPermissionsInherith() {
            let $table = $('#inherited-permissions-table');
            let $button = $('#permissions-inherith-update');
            let api;
    
            $table.DataTable({
                "ajax": {
                    "url": $table.data('route'),
                    "dataSrc": "data.permissions"
                },
                "order": [],
                "columns": [
                    {"data": "id"},
                    {"data": "description"}
                ],
                "initComplete": function () {
                    api = this.api()
                    api.buttons().container()
                        .appendTo($('#permissions-inherith-table-buttons'));
                }
            });
    
            $button.click(function () {
                $button.button('loading');
                $table.on('draw.dt', function () {
                    $button.button('reset');
                });
            });
        }
    
        // List Permissions  Users
        listPermissionsUsers() {
            let $table = $('#direct-permissions-table');
            let $button = $('#permissions-user-update');
            let api;
    
            $table.DataTable({
                "ajax": {
                    "url": $table.data('route'),
                    "dataSrc": "data.permissions"
                },
                "order": [],
                "columns": [
                    {"data": "id"},
                    {"data": "description"}
                ],
                "initComplete": function () {
                    api = this.api()
                    api.buttons().container()
                        .appendTo($('#permissions-user-table-buttons'));
                }
            });
    
            $button.click(function () {
                $button.button('loading');
                $table.on('draw.dt', function () {
                    $button.button('reset');
                });
            });
        }

     // Assign role to user
     assignRoleToUser() {
        let $button = $('#save');
        let $form = $('#save-form');
        clearForm($form);
        $button.click(function () {
            $button.button('loading');
            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                data: $form.serialize()
            }).done(function (json) {
                swalSuccessWithButton(json);
                clearForm($form);
                setTimeout(() => {
                    location.reload();
                }, 500)
            }).fail(function (json) {
                swalError(json);
            }).always(function () {
                $button.button('reset');
            });
        });
    }

    

         // edit roles  no sirve
         editRoleUsers() {
            let $button = $('#role-edit-user');
            let $form = $('#role-user-form');    
            $button.click(function () {
                $button.button('loading');
                $.ajax({
                    url: $form.attr('action'),
                    method: 'post',
                    data: $form.serialize()
                }).done(function (json) {
                    $form.trigger('reset');
                    $('#edit-role-users').modal('hide');
                    setTimeout(() => {
                        location.reload();
                    }, 500)
                    swalSuccessWithButton(json);
                }).fail(function (json) {
                    swalError(json);
                }).always(function () {
                    $button.button('reset');
                });
            });
        }

            // Init Select
        select2permission(placeholder) {
        $('#permissionselect').select2({
            width: '100%',
            placeholder: placeholder,
            allowClear: true,
            language: 'es'
        });
    }
     // Assign permissions to user
     assignPermissionToUser() {      
        let $button = $('#save');
        let $form = $('#save-form');
        clearForm($form);
        $button.click(function () {
            $button.button('loading');
            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                data: $form.serialize()
            }).done(function (json) {
                swalSuccessWithButton(json);
                clearForm($form);
                setTimeout(() => {
                    location.reload();
                }, 500)
            }).fail(function (json) {
                swalError(json);
            }).always(function () {
                $button.button('reset');
            });
        });
    }

    //list permissions users
    permissionsUsers(){
            let $table = $('#permissions-users-table');
            $("#useredit").select2({
                width : '100%',
                placeholder: 'hola'
            });
            $("#permissionsedit").select2({
                width : '100%'
            });
            $table.DataTable({
                "ajax": {
                    "url": $table.data('route'),
                    "dataSrc": "data.users"
                },
                "order": [],
                "columns": [
                    {"data": "userId"},
                    {"data": "username"},
                    {"data": "permissionsData"},
                    {"data":"actions", "className": "text-right"},
                ],
                "initComplete": function () {
                    let api = this.api();
                    api.buttons().container()
                        .appendTo($('#manage-role-table-buttons'));      
                $(document).on('click', '.edit', function () {
                    let $button = $(this);
                    var array = [];
                    var result = [];
                    var data = api.row($(this).parents("tr") ).data();
                    console.log(data);
                    $("#useredit option").each(function (i){
                           if ($(this).attr('value') == data.id ){
                            result.push($(this).attr('value'));
                           }
                    })
                    $("#useredit").select2().val(result).trigger('change');
                    $("#hiddenuser").val(result); 
                    $(data.permissions).each(function (i,p){
                        $("#permissionsedit option").each(function (i){
                           if ($(this).attr('value') == p.id ){
                            array.push($(this).attr('value'));
                           }
                        })
                    });
                    $("#permissionsedit").select2().val(array).trigger('change');
            })
            $(document).on('click', '.delete', function () {
                let $button = $(this);
                swalConfirm($button.data('route'), function () {
                    $table.DataTable().ajax.url($table.data('route')).load();
                });
            });
            },
                "drawCallback": function() {
                    $('.users').select2({
                        width : '100%'
                    });
                }
            });
            $table.on('xhr.dt', function (event, settings, json, xhr) {
                if (xhr.status === 500 || xhr.status === 422) {
                    swalError(xhr);
                }
            });
        }

         // edit permissions 
         editPermissionUsers() {
            let $button = $('#permission-edit-user');
            let $form = $('#permission-user-form');
            $button.click(function () {
                $button.button('loading');
                $.ajax({
                    url: $form.attr('action'),
                    method: 'post',
                    data: $form.serialize() 
                }).done(function (json) {
                    $form.trigger('reset');
                    $('#edit-permission-users').modal('hide');
                    setTimeout(() => {
                        location.reload();
                    }, 500)
                    swalSuccessWithButton(json);
    
                }).fail(function (json) {
                    swalError(json);
    
                }).always(function () {
                    $button.button('reset');
                });
            });
        }

        select2Roles(placeholder){
            let $user = $('#user');
            let $role = $('#roleselect');
            $user.select2().on('change', function() {
                $.ajax({
                    url:`${$role.data('route')}/${$user.val()}`,
                    type:'GET',
                    success:function(data) {
                        $role.empty();
                        console.log(data.data.roles);
                        $.each(data.data.roles, function(key,value) {
                            console.log(value.description);
                            $role.append($("<option></option>").attr("value", value.id).text(value.description));
                        });
                        $role.select2();
                    }
                });
            }).trigger('change');
    
        };

    // Revoke role to user
     excludeRoletoUser() {
        let $button = $('#save');
        let $form = $('#exclude-roles-user-form');
        clearForm($form);

        $button.click(function () {
            $button.button('loading');
            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                data: $form.serialize()
            }).done(function (json) {
                swalSuccessWithButton(json);
                clearForm($form);
                setTimeout(() => {
                    location.reload();
                }, 500)
            }).fail(function (json) {
                swalError(json);
            }).always(function () {
                $button.button('reset');
            });
        });
    }

     // Exclude roles user list
     excludeRolesUserList() {
        let $table = $('#exclude-roles-users-table');
        let $button = $('#update-exclude');
        let api;
        let $buttonUpdate = $('#save');
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.users"
            },
            "order": [
                [0, "asc"]
            ],
            "columns": [
                {"data": "userId"},
                {"data": "username"},
                {"data": "roles"},
                {"data": "date", "className": "text-right"}
            ],
            "initComplete": function () {
                api = this.api()
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });
    };

    select2Permissions(placeholder){
        let $user = $('#user');
        let $role = $('#permissionselect');
        $user.select2().on('change', function() {
            $.ajax({
                url:`${$role.data('route')}/${$user.val()}`,
                type:'GET',
                success:function(data) {
                    $role.empty();
                    console.log(data.data.permissions);
                    $.each(data.data.permissions, function(key,value) {
                        console.log(value.description);
                        $role.append($("<option></option>").attr("value", value.id).text(value.description));
                    });
                    $role.select2();
                }
            });
        }).trigger('change');
    };

    // Revoke role to user
    excludePermissionstoUser() {
        let $button = $('#save');
        let $form = $('#exclude-permissions-user-form');
        clearForm($form);
        $button.click(function () {
            $button.button('loading');
            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                data: $form.serialize()
            }).done(function (json) {
                swalSuccessWithButton(json);
                clearForm($form);
                setTimeout(() => {
                    location.reload();
                }, 500)
            }).fail(function (json) {
                swalError(json);
            }).always(function () {
                $button.button('reset');
            });
        });
    }

     // Exclude permissions user list
     excludePermissionsUserList() {
        let $table = $('#exclude-permissions-users-table');
        let $button = $('#update-exclude');
        let api;
        let $buttonUpdate = $('#save');
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.users"
            },
            "order": [
                [0, "asc"]
            ],
            "columns": [
                {"data": "userId"},
                {"data": "username"},
                {"data": "permissions"},
                {"data": "date", "className": "text-right"}
            ],
            "initComplete": function () {
                api = this.api()
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });
        };
   };


window.Security = Security;
