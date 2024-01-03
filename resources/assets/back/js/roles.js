import {} from "../../commons/js/core";

class Roles {
    initTableRoles () {
        /*$('#table-roles').DataTable( {
            fixedHeader: true,
            responsive: true
        });*/

        $('#table-roles').DataTable( {
            ajax: 'https://dev-back.bestcasinos.lat/agents/get/direct-children?draw=2&start=0',
            processing: true,
            serverSide: true,
            columnDefs: [{
                "defaultContent": "-",
                "targets": "_all"
            }]
        });

        $('.table-load').addClass('table-complete');
        $('.page-role .loading-style').hide();
    }
}

window.Roles = Roles;
