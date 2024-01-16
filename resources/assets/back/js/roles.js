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
                buttons.find('[data-toggle="modal"]').attr('data-userid', aData[2]).attr('data-username', aData[0]);
                buttons.find('.roleSimple').attr('href', '/agents/role/' + aData[0]);
                $('td:eq(0)', nRow).html('<span class="btn-tr-details"><i class="fa-regular fa-eye"></i></span> ' + aData[0]);
                $('td:eq(1)', nRow).html('<span class="deco-rol">'+ aData[1] +'</span>');
                $('td:eq(4)', nRow).html('$'+ aData[4]);
                $('td:eq(5)', nRow).attr('data-id', aData[2]).addClass('text-right').html(buttons.html());
            },
            initComplete: function() {

            },
        });

        $('.table-load').addClass('table-complete');
        $('.page-role .loading-style').hide();

        $(document).on('click','.currentDataRole', function (){
            let $username = $(this).data('username');
            let $userid = $(this).data('userid');

            $('.username-form').html($username);
            Roles.globalusername = $username;
            Roles.globaluserid = $userid;
        });
    }

    userResetPassword() {
        let button = '.resetUserPassword';
        let route = $(button).data('route');

        $(document).on('click', button, function () {
            let $this = $(this);
            let $password = $('#password-role-reset');
            let $data = {
                userId: Roles.globaluserid,
                newPassword: $password.val(),
            }

            if($password.val().length >= 8) {
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

    static errorResponse(json) {
        let array = Object.values(json.responseJSON.errors);
        let title = json.responseJSON.message;

        $.each(array, function (index, value) {
            Toastr.notifyToastr(title, value, 'error');
        })
    }
}

window.Roles = Roles;
