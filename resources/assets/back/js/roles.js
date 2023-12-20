import {} from "../../commons/js/core";

class Roles {
    initTableRoles () {
        $('#table-roles').DataTable( {
            fixedHeader: true,
            responsive: true
        });
    }
}

window.Roles = Roles;
