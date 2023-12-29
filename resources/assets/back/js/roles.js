import {} from "../../commons/js/core";

class Roles {
    initTableRoles () {
        $('#table-roles').DataTable( {
            fixedHeader: true,
            responsive: true
        });

        $('.table-load').addClass('table-complete');
        $('.page-role .loading-style').hide();
    }
}

window.Roles = Roles;
