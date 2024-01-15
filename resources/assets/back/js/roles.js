import {} from "../../commons/js/core";
class Roles {
    initTableRoles() {
        let table = $('#table-roles');
        let route = table.data('route');

        table.DataTable({
            ajax: route,
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
                $('td:eq(0)', nRow).html('<span class="btn-tr-details"><i class="fa-regular fa-eye"></i></span> ' + aData[0]);
                $('td:eq(1)', nRow).html('<span class="deco-rol">'+ aData[1] +'</span>');
                $('td:eq(4)', nRow).html('$'+ aData[4]);
                $('td:eq(5)', nRow).attr('data-id', aData[2]).addClass('text-right').html(buttons.html());
            }
        });

        $('.table-load').addClass('table-complete');
        $('.page-role .loading-style').hide();

        $(document).on('click','.currentDataRole', function (){
            let username = $(this).data('username');
            let userid = $(this).data('userid');

            $('#globalActionID').data('userid', userid).data('username', username);
            $('.username-form').html(username);
        });
    }

    userResetPassword() {
        let button = $('.resetUserPassword');

    }
}

window.Roles = Roles;
