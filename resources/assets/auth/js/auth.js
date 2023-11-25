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
                Toastr.notifyToastr(json.data.title, json.data.message, 'success');
                setTimeout(() => window.location.href = json.data.route, 300);
            }).fail(function (json) {
                if (json.status === 404) {
                    if (json.responseJSON.data.changePassword === true) {
                        changePassword();
                    } else {
                        Toastr.notifyToastr(json.responseJSON.data.title, json.responseJSON.data.message, 'error');
                    }
                } else {
                    errorResponse(json);
                }

                $button.button('reset');
            }).always(function () {

            });
        });

        function changePassword() {
            $('.modal-reset-password').fadeIn();

            setTimeout(function (){
                $('#reset-password').focus();
            }, 300);

            $(document).on('click', '.btn-reset-password', function () {
                let $this = $(this);
                let $modal = $('.modal-reset-password');
                let $route = $this.data('route');
                let $password = $('#reset-password');
                let $passwordVal = $password.val();
                let $user = $('#username');
                let $userVal = $user.val();
                let $passwordForm = $('#password');
                let $oldPassword = $passwordForm.val();
                let $data = {
                    newPassword: $passwordVal,
                    repeatNewPassword: $passwordVal,
                    pUsername: $userVal,
                    oldPassword: $oldPassword,
                }

                $this.button('loading');

                $.ajax({
                    url: $route,
                    method: 'post',
                    data: $data
                }).done(function (json) {
                    $button.button('loading');
                    $modal.hide();
                    Toastr.notifyToastr(json.data.title, $modal.data('success'), 'success', 15000);

                    $user.val($userVal);
                    $passwordForm.val($passwordVal);
                    $user.addClass('disabled');
                    $passwordForm.addClass('disabled');
                    $passwordForm.attr('type','password');
                    $('.btn-show-pass').remove();

                    setTimeout(function (){
                        $button.click();
                    }, 3000)
                }).fail(function (json) {
                    errorResponse(json);
                }).always(function () {
                    $this.button('reset');
                });
            });
        }

        function errorResponse(json) {
            let array = Object.values(json.responseJSON.errors);
            let title = json.responseJSON.message;

            $.each(array, function (index, value) {
                Toastr.notifyToastr(title, value, 'error');
            })
        }

        $('#login-form').keypress(function (event) {
            if (event.keyCode === 13) {
                $button.click();
            }
        });

        $('.modal-reset-password').keypress(function (event) {
            if (event.keyCode === 13) {
                $('.btn-reset-password').click();
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

        $(document).on('click', '.modal-reset-password .reset-password-close,.modal-reset-password .reset-password-bg', function () {
            $('.modal-reset-password').hide();
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
