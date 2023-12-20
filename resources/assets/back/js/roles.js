import {} from "../../commons/js/core";

class Roles {
    static loadRoles () {
        $('#table-roles').DataTable( {
            fixedHeader: true,
            responsive: true
        });
    }
}

window.Roles = Roles;
