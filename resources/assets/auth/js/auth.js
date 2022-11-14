import {swalError, swalSuccessNoButton} from "../../commons/js/core";
import {setCookie} from "../../back/js/commons";

class Auth {
    // Login users
    static login() {
        let $button = $('#login');
        $button.click(function() {
            let $form = $('#login-form');
            $button.button('loading');

            $.ajax({
                url: $form.attr('action'),
                type: 'post',
                dataType: 'json',
                data: $form.serialize()

            }).done(function (json) {
                setCookie('language-js', json.data.language, 365)
                swalSuccessNoButton(json);
                setTimeout(() => window.location.href = json.data.route, 1000);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });

        $('#login-form').keypress(function (event) {
            if (event.keyCode === 13) {
                $button.click();
            }
        });

        $('.languages-menu').click(function () {
            $(this).children('.languages-submenu').slideToggle();
        });
    }
}
window.Auth = Auth;
