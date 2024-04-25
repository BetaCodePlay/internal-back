import {swalError} from "../../commons/js/core";

/*let jsHeader = $('#js-header');
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
});*/

$(function () {
    /*[ Show pass ]*/
    let showPass = 0;
    $(document).on('click', '.btn-show-pass', function(){
        if(showPass === 0) {
            $(this).next('input').attr('type','text');
            $(this).find('i').removeClass('fa-eye-slash');
            $(this).find('i').addClass('fa-eye');
            showPass = 1;
        }
        else {
            $(this).next('input').attr('type','password');
            $(this).find('i').removeClass('fa-eye');
            $(this).find('i').addClass('fa-eye-slash');
            showPass = 0;
        }
    });
});

$(document).on('click', '.u-sidebar-navigation-v1-menu-item-search a', function (){
    $('.collapse-menu-action-s').click();

    setTimeout(function (){
        $(".form-control-sidebar").focus();
    }, 300)
})
