import {} from "../../commons/js/core";

class Roles {
    initTableRoles () {
        $('#table-roles').DataTable( {
            fixedHeader: true,
            responsive: true
        });

        setTimeout(function (){
            $('.table-load').addClass('table-complete');
            $('.page-role .loading-style').hide();
        }, 300)
    }
}

window.Roles = Roles;
