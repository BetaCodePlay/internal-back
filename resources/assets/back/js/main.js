import {setCookie} from "../../back/js/commons";


class Global {
    // Sidebar controllers
    static sidebar($button, $form) {
        $('.collapse-menu-action-s').on('click', function (){
            $('.collapse-menu-action').click()
        });

        $(document).on('click', '.action-mobile-menu', function (){
            $('body').toggleClass('no-overflow');
            $('.u-sidebar-navigation--dark').fadeToggle(150);
        });
    }
}
window.Global = Global;
