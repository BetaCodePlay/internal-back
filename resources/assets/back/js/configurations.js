import {clearForm, initFileInput, initSelect2} from "./commons";
import {swalConfirm, swalError, swalSuccessWithButton} from "../../commons/js/core";

class Configurations {
    // Credentials configurations
    credentials(){
        initSelect2();
        let $table = $('#credential-table');
        let $button = $('#update-credential');
        let provider = $('#provider').val();
        let api;
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.credentials"
            },
            "order": [[0, "asc"]],
            "columns": [
                {"data": "client"},
                {"data": "setting"},
                {"data": "currency_iso"},
                {"data": "percentage"},
                {"data": "status"},
                {"data": "actions", "className": "text-right"},
            ],
            "initComplete": function () {
                api = this.api()
                api.buttons().container()
                    .appendTo($('#table-buttons'));
                $(document).on('click', '.delete', function () {
                    let $button = $(this);
                    swalConfirm($button.data('route'), function () {
                        $table.DataTable().ajax.url($table.data('route')).load();
                    });
                });
            }
        });
        $button.click(function () {
            $button.button('loading');
            provider = $('#provider').val();
            let route = `${$table.data('route')}/${provider}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    };
    
     // Credentials configurations
     providerCredentials(){
        initSelect2();
        let $table = $('#credential-table');
        let $button = $('#update');
        let api;
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.credentials"
            },
            "order": [[0, "asc"]],
            "columns": [
                {"data": "client"},
                {"data": "type"},
                {"data": "currency_iso"},
                {"data": "provider"},
                {"data": "percentage"},
                {"data": "actions", "className": "text-right"},
            ],
            "initComplete": function () {
                api = this.api()
                api.buttons().container()
                    .appendTo($('#table-buttons'));
                $(document).on('click', '.delete', function () {
                    let $button = $(this);
                    swalConfirm($button.data('route'), function () {
                        $table.DataTable().ajax.url($table.data('route')).load();
                    });
                });
            }
        });
        $button.click(function () {
            $button.button('loading');
            let provider = $('#exclude_providers').val();
            console.log(provider);
            let currency = $('#currency').val();
            let client = $('#client').val();
            let type = $('#provider_type').val();
            let route = `${$table.data('route')}/${client}/${type}/${currency}/${provider}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });

            $table.on('xhr.dt', function (event, settings, json, xhr) {
                if (xhr.status === 500 || xhr.status === 422) {
                    swalError(xhr);
                    $button.button('reset');
                }
            });
        });
    };

    // Levels configuration
    levels() {
        initSelect2();
        let $form = $('#levels-form');
        let $loadButton = $('#load-button');
        let $updateButton = $('#update-button');

        $loadButton.click(function () {
            $loadButton.button('loading');

            $.ajax({
                url: $form.data('levels-route'),
                method: 'get'

            }).done(function (json) {
                $.each(json.data.levels, function (index, val) {
                    $(`input[name="levels[${index}]"]`).val(val.name);
                });
                $updateButton.removeAttr('disabled');

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $loadButton.button('reset');
            });
        });

        $updateButton.click(function () {
            $updateButton.button('loading');

            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                data: $form.serialize()

            }).done(function (json) {
                swalSuccessWithButton(json);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $updateButton.button('reset');
            });
        });
    }

    // Main route configuration
    mainRoute() {
        initSelect2();
        let $form = $('#routes-form');
        let $loadButton = $('#load-button');
        let $updateButton = $('#update-button');

        $loadButton.click(function () {
            $loadButton.button('loading');

            $.ajax({
                url: $form.data('levels-route'),
                method: 'get'

            }).done(function (json) {
                let configurations = json.data;
                $('#desktop_main').val(configurations.desktop.main).trigger('change');
                $('#desktop_auth').val(configurations.desktop.auth).trigger('change');
                $('#desktop_ssl').attr('checked', configurations.desktop.ssl);
                $('#mobile_main').val(configurations.mobile.main).trigger('change');
                $('#mobile_auth').val(configurations.mobile.auth).trigger('change');
                $('#mobile_ssl').attr('checked', configurations.mobile.ssl);
                $updateButton.removeAttr('disabled');

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $loadButton.button('reset');
            });
        });

        $updateButton.click(function () {
            $updateButton.button('loading');

            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                data: $form.serialize()

            }).done(function (json) {
                swalSuccessWithButton(json);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $updateButton.button('reset');
            });
        });
    }

    // providers
    providers() {
        let $table = $('#provider-table');
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.providers"
            },
            "order": [],
            "columns": [
                {"data": "name"},
                {"data": "status"},
            ],
            "initComplete": function () {
                let api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });
    };

    // Registration login
    registrationLogin() {
        initSelect2();
        let $form = $('#registration-login-form');
        let $loadButton = $('#load-button');
        let $updateButton = $('#update-button');

        $loadButton.click(function () {
            $loadButton.button('loading');

            $.ajax({
                url: $form.data('registration-login-route'),
                method: 'get'

            }).done(function (json) {
                let registration = json.data.registration;
                let login = json.data.login;
                $('#allow_registration').attr('checked', registration.allow);
                $('#facebook_registration').attr('checked', registration.social.facebook);
                $('#google_registration').attr('checked', registration.social.google);
                $('#allow_login').attr('checked', login.allow);
                $('#facebook_login').attr('checked', login.social.facebook);
                $('#google_login').attr('checked', login.social.google);
                $updateButton.removeAttr('disabled');

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $loadButton.button('reset');
            });
        });

        $updateButton.click(function () {
            $updateButton.button('loading');

            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                data: $form.serialize()

            }).done(function (json) {
                swalSuccessWithButton(json);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $updateButton.button('reset');
            });
        });
    }

    // Registration credential
    save() {
        initSelect2();
        let $form = $('#save-form');
        let $button = $('#save');
        $button.click(function () {
            $button.button('loading');
            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                dataType: 'json',
                data:  $('#save-form').serialize()
            }).done(function (json) {
                $('save-form').trigger('reset');
                clearForm($form);
                swalSuccessWithButton(json);
            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }

    // Template
    template() {
        initSelect2();
        initFileInput();
        let $form = $('#template-form');
        let $loadButton = $('#load-button');

        $('#template').change(function () {
            let $theme = $('#theme');
            let template = $(this).val();
            $theme.find('option').remove();

            $.ajax({
                url: $form.data('themes-route'),
                method: 'get',
                data: {
                    template
                }
            }).done(function (json) {
                $.each(json.data.themes, function (index, val) {
                    $theme.append(`<option value="${val}">${val.ucwords()}</option>`);
                });

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $loadButton.button('reset');
            });
        });
    }

    // Update credential
    update() {
        initSelect2();
        let $form = $('#posts-form');
        let $button = $('#update');

        $button.click(function () {
            $button.button('loading');
            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                dataType: 'json',
                data: $('#posts-form').serialize()

            }).done(function (json) {
                swalSuccessWithButton(json);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }
    
     // Provider types
     providerTypes() {
        initSelect2();
        $('#currency').on('change', function () {
            let currency = $('#currency').val();
            let route = $(this).data('route');
            let $providerTypes = $('#provider_type');

            if (currency !== '') {
                $.ajax({
                    url: route,
                    type: 'get',
                    dataType: 'json',
                    data: {
                        currency: currency
                    }
                }).done(function (json) {
                    $(json.data.provider_types).each(function (key, element) {
                        $providerTypes.append("<option value=" + element.id + ">" + element.name + "</option>");
                    })
                    $providerTypes.prop('disabled', false);
                }).fail(function (json) {

                });
            } else {
                $providerTypes.val('');
            }
        });
    }

    // Providers
    providers() {
        initSelect2();
        $('#provider_type').on('change', function () {
            let provider = $('#provider_type').val();
            let route = $(this).data('route');
            let $excludeProvider = $('#exclude_providers');

            if (provider !== '') {
                $.ajax({
                    url: route,
                    type: 'get',
                    dataType: 'json',
                    data: {
                        providers: provider
                    }
                }).done(function (json) {
                    $(json.data.exclude_providers).each(function (key, element) {
                        $excludeProvider.append("<option value=" + element.id + ">" + element.name + "</option>");
                    })
                    $excludeProvider.prop('disabled', false);
                }).fail(function (json) {

                });
            } else {
                $excludeProvider.val('');
            }
        });
    }




}

window.Configurations = Configurations;
