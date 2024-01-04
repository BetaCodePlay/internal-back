import {} from "../../commons/js/core";

class Roles {
    initTableRoles() {
        /*$('#table-roles').DataTable( {
            fixedHeader: true,
            responsive: true
        });*/

        $('#table-roles').DataTable({
            ajax: 'https://dev-back.bestcasinos.lat/agents/get/direct-children?draw=2&start=0',
            processing: true,
            serverSide: true,
            columnDefs: [{
                "defaultContent": "-",
                "targets": "_all"
            }],
            fixedHeader: true,
            responsive: true,
            fnCreatedRow: function (nRow, aData, iDataIndex) {
                let options = '<div class="d-inline-block dropdown">\n' +
                    '<button class="btn dropdown-toggle" type="button" id="dropdownRoleProfile" data-toggle="dropdown" aria-expanded="false">\n' +
                    '    <i class="fa-solid fa-ellipsis-vertical"></i>\n' +
                    '</button>\n' +
                    '<ul class="dropdown-menu" aria-labelledby="dropdownRoleProfile">\n' +
                    '   <li><a class="dropdown-item" href="#">View profile</a></li>\n' +
                    '   <li><a class="dropdown-item" href="javascript:void(0)" data-toggle="modal" data-target="#role-create-simple">Add role</a></li>\n' +
                    '   <li><a class="dropdown-item" href="javascript:void(0)" data-toggle="modal" data-target="#role-password-reset">Reset password</a></li>\n' +
                    '   <li><a class="dropdown-item" href="javascript:void(0)" data-toggle="modal" data-target="#role-lock">Lock profile</a></li>\n' +
                    '   <li><a class="dropdown-item" href="javascript:void(0)" data-toggle="modal" data-target="#role-balance">Balance adjustment</a></li>\n' +
                    '</ul>\n' +
                    '</div>\n' +
                    '\n' +
                    '<a href="#" class="btn btn-href"><i class="fa-solid fa-chevron-right"></i></a>';

                $('td:eq(0)', nRow).html('<span class="btn-tr-details"><i class="fa-regular fa-eye"></i></span> ' + aData[0]);
                $('td:eq(5)', nRow).addClass('text-right').html(options);
            }
        });

        /*"fnCreatedRow": function(nRow,aData,iDataIndex) {
            var new_price = parseFloat(aData.price) + 0.1 * parseFloat(aData.price);
            $('td:eq(3)',nRow).html(new_price);
        }

        $(nRow).attr('class', 'newClass');*/

        $('.table-load').addClass('table-complete');
        $('.page-role .loading-style').hide();
    }
}

window.Roles = Roles;
