class Roles {
    static loadRoles () {
        new DataTable('#example', {
            fixedHeader: true,
            responsive: true
        });

        console.log('loag')
    }
}

window.Roles = Roles;

console.log('loag 2')
