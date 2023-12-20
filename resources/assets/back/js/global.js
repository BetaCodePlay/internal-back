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
}).done(function (json) {
    if (json && json.data) {
        let { balance, user } = json.data;
        let { id } = user;

        let amountRefresh = $(`.amount-refresh-${id}`);
        amountRefresh.text(balance);
    } else {
        console.error('La respuesta no tiene la estructura esperada:', response);
    }
}).fail(function (json) {
    swalError(json);
});
