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

                buttons.find('[data-toggle="modal"]').attr('data-userid', aData[2]).attr('data-username', aData[0]);
                buttons.find('.roleSimple').attr('href', '/agents/role/' + aData[0]);
                buttons.find(modalLockTarget).attr('data-value', aData[3][1]).html(aData[3][1] === true ? $(modalLockTarget).data('lock') : $(modalLockTarget).data('unlock')).attr('data-type', aData[3][2]);

                $('td:eq(0)', nRow).html('<span class="btn-tr-details"><i class="fa-regular fa-eye"></i></span> ' + aData[0]);
                $('td:eq(1)', nRow).html('<span class="deco-rol">' + aData[1] + '</span>');
                $('td:eq(3)', nRow).html('<i class="fa-solid i-status fa-circle '+ (aData[3][1] === true ? 'green' : 'red') + '"></i> ' + aData[3][0]);
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
        let $title;

        $(document).on('click', $button, function () {
            let $this = $(this);
            let $route = $('#userLockType').data('route');
            let $data = {
                userId: Roles.globaluserid,
                description: $('#userReason').val(),
                lock_users: true,
                type: true,
            }

            $.ajax({
                url: $route,
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

        $(document).on('click', $targetModal, function () {
            let $this = $(this);
            let $val = $this.data('value');
            let $type = $modal.data('type');
            let $buttonCancel = $modal.find('.modal-footer [data-dismiss="modal"]');
            let $buttonSuccess = $modal.find('.lockUser');
            let $cancel;
            let $success;

            if ($val === true) {
                $title = $this.data('lock');
                $cancel = $buttonCancel.data('lock');
                $success = $buttonSuccess.data('lock');
                $('#userReasonUnlock').hide();
                $('#userReasonLock').show();
            } else {
                $title = $this.data('unlock');
                $cancel = $buttonCancel.data('unlock');
                $success = $buttonSuccess.data('unlock');
                $('#userReasonLock').hide();
                $('#userReasonUnlock').show();
            }

            console.log($type);

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

    static errorResponse(json) {
        let array = Object.values(json.responseJSON.errors);
        let title = json.responseJSON.message;

        $.each(array, function (index, value) {
            Toastr.notifyToastr(title, value, 'error');
        })
    }
}

window.Roles = Roles;
