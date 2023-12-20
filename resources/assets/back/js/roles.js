import {} from "../../commons/js/core";

class Roles {
    loadRoles () {
        $('#table-roles').DataTable( {
            fixedHeader: true,
            responsive: true
        });
    }
}

window.Roles = Roles;
