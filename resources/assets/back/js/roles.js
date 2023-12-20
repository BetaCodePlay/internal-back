import {} from "../../commons/js/core";

class Roles {
    loadRoles () {
        new DataTable('#example', {
            fixedHeader: true,
            responsive: true
        });

        console.log('loag')
    }
}

window.Roles = Roles;

console.log('loag 2')
