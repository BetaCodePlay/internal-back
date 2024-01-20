import {} from "../../commons/js/core";

class Roles {
    static globalusername;
    static globaluserid;

    initTableRoles() {
        let $table = $('#table-roles');
        let $route = $table.data('route');

        $table.DataTable({
            ajax: $route,
            processing: true,
            serverSide: true,
            columnDefs: [{
                "defaultContent": "-",
                "targets": "_all"
            }],
            fixedHeader: true,
            responsive: true,
            fnCreatedRow: function (nRow, aData, iDataIndex) {
                let buttons = $('#user-buttons');
                let modalLockTarget = '[data-target="#role-lock"]';
                let modalResetPasswordTarget = '[data-target="#role-password-reset"]';

                buttons.find('[data-toggle="modal"]').attr('data-userid', aData[2]).attr('data-username', aData[0]);
                buttons.find('.roleSimple').attr('href', '/agents/role/' + aData[0]);
                buttons.find(modalLockTarget).attr('data-value', aData[3][1]).html(aData[3][1] ? $(modalLockTarget).data('lock') : $(modalLockTarget).data('unlock')).attr('data-type', aData[3][2]);

                if (aData[3][1]) {
                    buttons.find(modalResetPasswordTarget).removeClass('d-none');
                } else {
                    buttons.find(modalResetPasswordTarget).addClass('d-none');
                }

                $('td:eq(0)', nRow).html('<span class="btn-tr-details"><i class="fa-regular fa-eye"></i></span> ' + aData[0]);
                $('td:eq(1)', nRow).html('<span class="deco-rol">' + aData[1] + '</span>');
                $('td:eq(3)', nRow).html('<i class="fa-solid i-status fa-circle ' + (aData[3][1] ? 'green' : 'red') + '"></i> ' + aData[3][0]);
                $('td:eq(4)', nRow).html('$' + aData[4]);
                $('td:eq(5)', nRow).attr('data-id', aData[2]).addClass('text-right').html(buttons.html());
            },
            initComplete: function () {

            },
        });

        $('.table-load').addClass('table-complete');
        $('.page-role .loading-style').hide();

        $(document).on('click', '.currentDataRole', function () {
            let $username = $(this).data('username');
            let $userid = $(this).data('userid');

            $('.username-form').html($username);
            Roles.globalusername = $username;
            Roles.globaluserid = $userid;
        });
    }

    userResetPassword() {
        let button = '.resetUserPassword';

        $(document).on('click', button, function () {
            let $this = $(this);
            let route = $(button).data('route');
            let $password = $('#password-role-reset');
            let $data = {
                userId: Roles.globaluserid,
                newPassword: $password.val(),
            }

            if ($password.val().length >= 8) {
                $this.button('loading');

                $.ajax({
                    url: route,
                    method: 'post',
                    data: $data
                }).done(function (json) {
                    Toastr.notifyToastr(json.data.title, json.data.message, 'success');
                }).fail(function (json) {
                    Roles.errorResponse(json);
                }).always(function () {
                    $this.button('reset');
                });
            } else {
                Toastr.notifyToastr('Error', $password.attr('placeholder'), 'error');
            }
        });
    }

    userLock() {
        let $button = '.lockUser';
        let $targetModal = '[data-target="#role-lock"]';
        let $modal = $('#role-lock');
        let $globalLock;
        let $globalType;
        let $title;

        $(document).on('click', $button, function () {
            let $this = $(this);
            let $route = $('#userLockType').val();
            let $descriptionLock = $('#userReasonLock select').val();
            let $descriptionUnlock = $('#userReasonUnlock select').val();
            let $data = {
                userId: Roles.globaluserid,
                descriptionLock: $descriptionLock,
                descriptionUnlock: $descriptionUnlock,
                lockType: $globalLock
            }

            if ($globalType !== 8 && !$globalLock) {
                $route = $('#lockTypeThis').val();
            }

            $.ajax({
                url: $route,
                method: 'post',
                data: $data
            }).done(function (json) {
                $modal.modal('hide');
                Toastr.notifyToastr(json.data.title, json.data.message, 'success');
            }).fail(function (json) {
                Roles.errorResponse(json);
            }).always(function () {
                $this.button('reset');
            });
        });

        $(document).on('click', $targetModal, function () {
            let $this = $(this);
            let $type = $this.data('type');
            let $buttonCancel = $modal.find('.modal-footer [data-dismiss="modal"]');
            let $buttonSuccess = $modal.find('.lockUser');
            let $cancel;
            let $success;
            let $typeAll = $('#lockTypeAll');
            $globalLock = $this.data('value');

            $globalType = $type;
            $typeAll.show();

            if ($globalLock) {
                $title = $this.data('lock');
                $cancel = $buttonCancel.data('lock');
                $success = $buttonSuccess.data('lock');
                $('#userReasonUnlock').hide();
                $('#userReasonLock').show();
            } else {
                if ($type !== 8) {
                    $typeAll.hide();
                }

                $title = $this.data('unlock');
                $cancel = $buttonCancel.data('unlock');
                $success = $buttonSuccess.data('unlock');
                $('#userReasonLock').hide();
                $('#userReasonUnlock').show();
            }

            $modal.find('.modal-title').html($title);
            $buttonCancel.html($cancel);
            $buttonSuccess.html($success);
        });
    }

    userBalance() {
        let button = '.balanceUser';

        $(document).on('click', button, function () {
            let $this = $(this);
            let route = $(button).data('route');
            let $balance = $(button).data('balance');

            alert('here');
            return;
            let $data = {
                userId: Roles.globaluserid,
                userAmount: $('#userBalanceAmount').val(),
                userBalance: $balance
            }

            $.ajax({
                url: route,
                method: 'post',
                data: $data
            }).done(function (json) {
                Toastr.notifyToastr(json.data.title, json.data.message, 'success');
            }).fail(function (json) {
                Roles.errorResponse(json);
            }).always(function () {
                $this.button('reset');
            });
        });
    }

    userCreate() {
        let $button = '.createUser';
        let $globalType;
        let $dependence;

        $(document).on('change', '#createRolType', function () {
            $globalType = $(this).val();

            if ($globalType === '') {
                $('.d-agent').addClass('d-none')
            } else {
                $('.d-agent').removeClass('d-none')
            }
        });

        $(document).on('click', $button, function () {
            let $this = $(this);
            let $route;
            let $data = {
                username: $('#createRolUsername').val(),
                type: $('#createRolType').val(),
                percentage: $('#createRolPercentage').val(),
                password:  $('#createRolPassword').val(),
                dependence:  $dependence
            };

            if($('#createRolType').length > 0) {
                if($globalType === '') {
                    $route = $this.data('route-player');
                } else {
                    $route = $this.data('route-agent');
                }
            } else {
                $route = $this.data('route-player');
            }


            console.log($route);

           /* $.ajax({
                url: $route,
                method: 'post',
                data: $data
            }).done(function (json) {
                Toastr.notifyToastr(json.data.title, json.data.message, 'success');
            }).fail(function (json) {
                Roles.errorResponse(json);
            }).always(function () {
                $this.button('reset');
            });*/
        });
    }

    static errorResponse(json) {
        let array = Object.values(json.responseJSON.errors);
        let title = json.responseJSON.message;

        $.each(array, function (index, value) {
            Toastr.notifyToastr(title, value, 'error');
        })
    }
}

window.Roles = Roles;
