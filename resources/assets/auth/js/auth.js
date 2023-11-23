import {swalError, swalSuccessNoButton} from "../../commons/js/core";
import {setCookie} from "../../back/js/commons";

class Auth {
    // Login users
    static login() {
        let $button = $('#login');
        $button.click(function () {
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
                if (json.responseJSON.data.changePassword === true) {
                    $('#change-password').modal('show');
                    let $button = $('#update-button');
                    let $formCP = $('#change-password-form');
                    $('#change-password').on('shown.bs.modal', function (event) {
                        $('#pUsername').val(json.responseJSON.data.username);
                        $('#oldPassword').val(json.responseJSON.data.password);

                        $button.click(function () {
                            $button.button('loading');

                            $.ajax({
                                url: $formCP.attr('action'),
                                method: 'post',
                                data: $formCP.serialize()

                            }).done(function (json) {
                                swalSuccessNoButton(json);
                                setTimeout(() => {
                                    $('#change-password').modal('hide');
                                }, 5000);


                            }).fail(function (json) {
                                swalError(json);

                            }).always(function () {
                                $button.button('reset');
                            });
                        });

                    });
                } else {
                    swalError(json);
                }

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

        $(document).on('click', '.btn-tab-login', function () {
            let $this = $(this);
            let $class = $this.data('tag');

            $('.btn-tab-login').removeClass('active');
            $this.addClass('active');

            $('.login-tag').removeClass('show-tag');
            $('.' + $class).addClass('show-tag');
            localStorage.setItem('login', $class);
        });

        function getLoginOption() {
            let $button = $('.btn-tab-login');
            let $select = localStorage.getItem('login');
            let $count = $button.length;

            if ($count > 0) {
                if ($select === null) {
                    $button.eq(0).click();
                } else {
                    $('[data-tag="' + $select + '"]').click();
                }
            }
        }

        getLoginOption();
    }
}

window.Auth = Auth;
