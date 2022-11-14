import {
    initDatepickerStartToday,
    initSelect2,
    initDateRangePickerEndToday,
    initFileInput, initLitepickerEndToday, clearForm
} from "./commons";
import {swalConfirm, swalError, swalSuccessWithButton} from "../../commons/js/core";
import moment from "moment";

class DotSuite {

    // Currency users
    currencyUsers() {
        $('#currency').on('change', function () {
            let currency = $(this).val();
            let route = $(this).data('route');
            let provider = $(this).data('provider');
            let users = $('#users');

            if (currency !== '') {
                $.ajax({
                    url: route,
                    type: 'get',
                    dataType: 'json',
                    data: {
                        currency,
                        provider
                    }
                }).done(function (json) {
                    users.val(null).trigger('change');
                    $(json.data.users).each(function (key, element) {
                        users.append("<option value=" + element.id + ">" + element.username + "</option>");
                    })
                    users.prop('disabled', false);

                }).fail(function (json) {

                });
            } else {
                users.val('');
            }
        });
    }

    // Credentials data
    credentialsData() {
        $('#providers').change(function () {
            switch ($(this).val()) {
                case '115': {
                    $('.grant-secret').removeClass('d-none');
                    break;
                }
                default: {
                    $('.grant-secret').addClass('d-none');
                    break;
                }
            }
        });
    };

    // Free spins list
    freeSpinsList() {
        initSelect2();
        let $table = $('#free-spins-table');
        let $button = $('#search');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.free_spins"
            },
            "order": [],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "reference"},
                {"data": "currency"},
                {"data": "amount"},
                {"data": "rounds"},
                {"data": "game"},
                {"data": "start_date"},
                {"data": "end_date"},
            ],
            "initComplete": function () {
                let api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));

                $button.click(function () {
                    $button.button('loading');
                    let user = $('#user').val();
                    let provider = $('#provider').val();
                    let status = $('#status').val();
                    let code_reference = $('#code_reference').val();
                    let route = `${$table.data('route')}?provider=${provider}&user=${user}&status=${status}&reference=${code_reference}`;
                    api.ajax.url(route).load();
                    $table.on('draw.dt', function () {
                        $button.button('reset');
                    });
                });
            }
        });

        $table.on('xhr.dt', function (event, settings, json, xhr) {
            if (xhr.status === 500 || xhr.status === 422) {
                swalError(xhr);
                $("#free-spins-table").dataTable().fnDestroy();
                //$table.DataTable().ajax.url($table.data('route')).load();
                $button.button('reset');
            }
        });
    }

    // Free spins caleta gaming list
    freeSpinsCancelList() {
        let $table = $('#free-spins-list-table');
        let $button = $('#search');

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.free_spins"
            },
            "order": [],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "reference"},
                {"data": "currency"},
                {"data": "amount"},
                {"data": "rounds"},
                {"data": "actions", "className": "text-right"},
            ],
            "initComplete": function () {
                let api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));

                $button.click(function () {
                    $button.button('loading');
                    let users = $('#users').val();
                    let provider = $('#provider').val();
                    let route = `${$table.data('route')}?user=${users}&provider=${provider}`;
                    api.ajax.url(route).load();
                    $table.on('draw.dt', function () {
                        $button.button('reset');
                    });
                });

                $(document).on('click', '.delete', function () {
                    let $button = $(this);
                    let users = $('#users').val();
                    let provider = $('#provider').val();
                    let route = `${$table.data('route')}?user=${users}&provider=${provider}`;
                    $('#user').val(null).trigger('change');
                    swalConfirm($button.data('route'), function () {
                        $table.DataTable().ajax.url(route).load();
                    });
                });
            }
        });

        $table.on('xhr.dt', function (event, settings, json, xhr) {
            if (xhr.status === 500 || xhr.status === 422) {
                swalError(xhr);
                $button.button('reset');
            }
        });
    }

    // Games totals report
    gamesTotals() {
        let picker = initLitepickerEndToday();
        let $table = $('#games-table');
        let $button = $('#update');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.games"
            },
            "order": [[3, 'desc']],
            "columns": [
                {"data": "name"},
                {"data": "platform"},
                {"data": "bets", "className": "text-right", "type": "num-fmt"},
                {"data": "average", "className": "text-right", "type": "num-fmt"},
                {"data": "played", "className": "text-right", "type": "num-fmt"},
                {"data": "won", "className": "text-right", "type": "num-fmt"},
                {"data": "profit", "className": "text-right", "type": "num-fmt"},
                {"data": "rtp", "className": "text-right", "type": "num-fmt"},
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');
            let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
            let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');
            let provider = $('#provider').val();
            let route = `${$table.data('route')}/${startDate}/${endDate}/${provider}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });

        $table.on('xhr.dt', function (event, setting, json, xhr) {
            $('#played').text(json.data.totals.played);
            $('#won').text(json.data.totals.won);
            $('#profit').text(json.data.totals.profit);
            $('#rtp').text(json.data.totals.rtp)
        });
    };

    // Most played games report
    mostPlayedGames() {
        let picker = initLitepickerEndToday();
        let $table = $('#most-played-games-table');
        let $button = $('#search');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.games"
            },
            "order": [[2, 'asc']],
            "columns": [
                {"data": "name"},
                {"data": "platform"},
                {"data": "bets", "className": "text-right", "type": "num-fmt"},
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');
            let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
            let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');
            let provider = $('#provider').val();
            let route = `${$table.data('route')}/${startDate}/${endDate}/${provider}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    };

    //provider currency
    providerCurrency(){
        $('#currency').on('change', function () {
            let currency = $(this).val();
            let route = $(this).data('route');
            let provider = $('#provider');
            if (currency !== '') {
                $.ajax({
                    url: route,
                    type: 'get',
                    dataType: 'json',
                    data: {
                        currency
                    }
                }).done(function (json) {
                    provider.val(null).trigger('change');
                    $(json.data.providers).each(function (key, element) {
                        provider.append("<option value=" + element.id + ">" + element.name + "</option>");
                    })
                    provider.prop('disabled', false);

                }).fail(function (json) {

                });
            } else {
                provider.val('');
            }
        });
    }

    //Store slot
    storeSlot() {
        initSelect2();
        initFileInput();
        initDatepickerStartToday();

        let $button = $('#store');
        let $form = $('#slot-store-form');

        $form.on('submit', function (event) {
            event.preventDefault();
            let formData = new FormData(this);

            $button.button('loading');
            $.ajax({
                url: $form.attr('action'),
                type: 'post',
                dataType: 'json',
                contentType: false,
                processData: false,
                cache: false,
                data: formData

            }).done(function (json) {
                $form.trigger('reset');
                clearForm($form);
                $('form select').val("").trigger('change');
                swalSuccessWithButton(json);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });

        $form.keypress(function (event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                $button.click();
            }
        });
    }

    // Store credentials
    storeCredentials() {
        initSelect2();
        let $form = $('#credentials-form');
        let $button = $('#save');

        $button.click(function () {
            $button.button('loading');
            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                dataType: 'json',
                data: $form.serialize()

            }).done(function (json) {
                $('credentials-form').trigger('reset');
                clearForm($form);
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
        $('#providers_type').on('change', function () {
            let providerTypes = $('#providers_type').val();
            let route = $(this).data('route');
            let $providers = $('#providers');

            if (providerTypes !== '') {
                $.ajax({
                    url: route,
                    type: 'get',
                    dataType: 'json',
                    data: {
                        type: providerTypes
                    }
                }).done(function (json) {
                    $(json.data.providers).each(function (key, element) {
                        $providers.append("<option value=" + element.id + ">" + element.name + "</option>");
                    })
                    $providers.prop('disabled', false);
                }).fail(function (json) {

                });
            } else {
                $providers.val('');
            }
        });
    }

    // Credentials
    credentails() {
        initSelect2();
        let $table = $('#credential-table');
        let $button = $('#update');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.credentials"
            },
            "order": [[0, "desc"]],
            "columns": [
                {"data": "client"},
                {"data": "provider_name"},
                {"data": "credential"},
                {"data": "currency_iso", "className": "text-right"},
                {"data": "percentage_credential"},
                {"data": "status_data"},
                {"data": "actions", "className": "text-right"}
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
                $(document).on('click', '.status', function () {
                    let $button = $(this);
                    swalConfirm($button.data('route'), function () {
                        $table.DataTable().ajax.url($table.data('route')).load();
                    });
                });
            }
        });

        $button.click(function () {
            $button.button('loading');
            let client = $('#client').val();
            let currencies = $('#currencies').val();
            let provider = $('#providers').val();
            let route = `${$table.data('route')}/${client}?provider=${provider}&currencies=${currencies}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    }

    //Type users
    typeFormUsers() {
        $('#type_user').change(function () {
            let type = $(this).val();
            switch (type) {
                case '1':
                    $('#segments').addClass('d-none');
                    $('#excel').addClass('d-none');
                    $('#exclude_users').addClass('d-none');
                    $('#user').removeClass('d-none');
                    break;
                case '2':
                    $('#user').addClass('d-none');
                    $('#excel').addClass('d-none');
                    $('#segments').removeClass('d-none');
                    $('#exclude_users').removeClass('d-none');
                    break;
                case '3':
                    $('#user').addClass('d-none');
                    $('#segments').addClass('d-none');
                    $('#excel').removeClass('d-none');
                    $('#exclude_users').removeClass('d-none');
                    break;
                default:
                    $('#user').addClass('d-none');
                    $('#segments').addClass('d-none');
                    $('#excel').addClass('d-none');
                    $('#exclude_users').addClass('d-none');
                    break;
            }
        });
    }

    // Users totals report
    usersTotals(support = false) {
        let picker;

        if (support) {
            initDateRangePickerEndToday();
        } else {
            picker = initLitepickerEndToday();
        }

        let $table = $('#users-table');
        let $button = $('#search');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.users"
            },
            "order": [[4, 'desc']],
            "columns": [
                {"data": "user"},
                {"data": "wallet"},
                {"data": "username"},
                {"data": "provider_name"},
                {"data": "bets", "className": "text-right", "type": "num-fmt"},
                {"data": "played", "className": "text-right", "type": "num-fmt"},
                {"data": "won", "className": "text-right", "type": "num-fmt"},
                {"data": "profit", "className": "text-right", "type": "num-fmt"},
                {"data": "rtp", "className": "text-right", "type": "num-fmt"},
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');
            let startDate;
            let endDate;

            if (support) {
                startDate = $('#start_date').val();
                endDate = $('#end_date').val();
            } else {
                startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
                endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');
            }
            let provider = $('#provider').val();
            let currency = $('#currency').val();
            let route = `${$table.data('route')}/${startDate}/${endDate}?currency=${currency}&provider=${provider}`;

            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });

        $table.on('xhr.dt', function (event, settings, json, xhr) {
            if (xhr.status === 500 || xhr.status === 422) {
                swalError(xhr);
                $button.button('reset');
            }

            $('#played').text(json.data.totals.played);
            $('#won').text(json.data.totals.won);
            $('#profit').text(json.data.totals.profit);
            $('#rtp').text(json.data.totals.rtp)
        });
    };
}

window.DotSuite = DotSuite;
