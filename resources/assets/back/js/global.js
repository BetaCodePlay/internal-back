import {swalError} from "../../commons/js/core";

let jsHeader = $('#js-header');
let authUserId = jsHeader.data('auth-user');
let userType = jsHeader.data('user-type');

$.ajax({
    url: jsHeader.data('route-find'),
    type: 'get',
    dataType: 'json',
    data: {
        id: authUserId,
        type: userType
    }
}).done(function (response) {
    if (response && response.data && response.data.user) {
        let user = response.data.user;
        let {id, balance} = user;

        let formattedBalance = parseFloat("" + balance).toFixed(2);
        let amountRefresh = $(`.amount-refresh-${id}`);
        amountRefresh.text(formattedBalance);
    } else {
        console.error('La respuesta no tiene la estructura esperada:', response);
    }
}).fail(function (json) {
    swalError(json);
});
