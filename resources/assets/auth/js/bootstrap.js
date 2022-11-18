import {setCookie} from "../../back/js/commons";

window.$ = window.jQuery = require('jquery');
window.Popper = require('popper.js').default;
require('bootstrap');
require('jquery.easing');
require('../../commons/plugins/bootstrap-button/js/bootstrap-button.min');
require('animsition');
require('moment');
require('daterangepicker');


// Change language
$('.change-language').click(function () {
    let locale = $(this).data('locale');
    setCookie('language-js', locale, 365);
})

$('.languages-menu .languages-submenu').hide();
$('.languages-menu').click(function(){
    let ddLeft = $(this).offset().left;
    let ddWidth = $(this).width();
    //let nuevoCSS = { " margin-right": '0px', "margin-inline-start" : '-80px' };
    $(this).siblings('.languages-submenu').slideToggle('slow');
});
