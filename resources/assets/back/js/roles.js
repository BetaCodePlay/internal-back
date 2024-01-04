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
                $('td:eq(0)', nRow).html('<span class="btn-tr-details"><i class="fa-regular fa-eye"></i></span> ' + aData[0]);
                $('td:eq(1)', nRow).html('<span class="deco-rol">'+ aData[1] +'</span>');
                $('td:eq(4)', nRow).html('$'+ aData[4]);
                $('td:eq(5)', nRow).attr('data-id', aData[2]).addClass('text-right').html($('#user-buttons').html());
            }
        });

        $('.table-load').addClass('table-complete');
        $('.page-role .loading-style').hide();
    }
}

window.Roles = Roles;
