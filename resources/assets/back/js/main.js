import {setCookie} from "../../back/js/commons";
$('.collapse-menu-action-s').on('click', function (){
    $('.collapse-menu-action').click()
});

$(document).on('click', '.action-mobile-menu', function (){
    console.log('action');

    $('.u-sidebar-navigation--dark').fadeToggle();
})

console.log('array')
