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
            }],
            fixedHeader: true,
            responsive: true,
            fnCreatedRow : function(nRow,aData,iDataIndex) {
                console.log(nRow);
                console.log(aData);
                console.log(iDataIndex);
                $('td:eq(0)',nRow).html('<span class="btn-tr-details"><i class="fa-regular fa-eye"></i></span> ' + aData[0]);
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
