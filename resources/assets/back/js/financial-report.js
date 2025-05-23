import {swalConfirm, swalError, swalSuccessNoButton} from "../../commons/js/core";
import {
    clearForm,
    initDatepickerStartToday,
    initDateRangePickerEndToday,
    initDateTimePicker,
    initSelect2
} from "./commons";

class FinancialReport {

    // All dates of financial report
    all() {
        let $table = $('#special-table');
        let $button = $('#search');
        let api;

        $table.DataTable({
            "ajax": {

                "url": $table.data('route'),
                "dataSrc": "data.financial"
            },
            "order": [[0, "asc"]],
            "columns": [
                {"data": "provider"},
                {"data": "makers"},
                {"data": "currency"},
                {"data": "amount"},
                {"data": "load_amount"},
                {"data": "totalPlayed"},
                {"data": "load_date"},
                {"data": "limit"},
                {"data": "actions", "className": "text-right"}
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
            let provider = $('#provider').val();
            let route = `${$table.data('route')}?provider=${provider}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    };


    // Maker
    maker() {
        initSelect2();
        $('#change_provider').on('change', function () {
            let provider = $('#change_provider').val();
            let route = $(this).data('route');
            let maker = $('#maker');
            if (provider !== '') {
                $.ajax({
                    url: route,
                    type: 'get',
                    dataType: 'json',
                    data: {
                        change_provider: provider
                    }
                }).done(function (json) {
                    maker.html('loading');
                    maker.html(json.data.maker);
                    $(json.data.maker).each(function (key, element) {
                        maker.append("<option value=" + element.maker + ">" + element.description + "</option>");
                    })
                    maker.prop('disabled', false);
                }).fail(function (json) {

                });
            } else {
                maker.val('');
            }
        });
    }

    //store
    store() {
        initSelect2();
        initDateTimePicker();
        let $form = $('#store-form');
        let $button = $('#store');
        let $table = $('#special-table');

        $button.click(function () {
            $button.button('loading');

            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                dataType: 'json',
                data: $form.serialize()

            }).done(function (json) {
                $('store-form').trigger('reset');
                $('form select').val(null).trigger('change');
                $table.DataTable().ajax.url($table.data('route')).load();
                swalSuccessNoButton(json);
                setTimeout(() => window.location.href = json.data.route, 1000);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }

    // Update
    update() {
        initSelect2();
        initDateTimePicker();
        let $button = $('#update');
        let $form = $('#update-form');

        $button.click(function () {
            $button.button('loading');

            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                dataType: 'json',
                data: $form.serialize()

            }).done(function (json) {
                $('update-form').trigger('reset');
                $('form select').val(null).trigger('change');
                swalSuccessNoButton(json);
                setTimeout(() => window.location.href = json.data.route, 1000);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    };

    // Advanced search
    search() {
        initDateRangePickerEndToday();
        initSelect2();

        let $table = $('#provider-table');
        let $button = $('#search');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.report"
            },
            "order": [],
            "columns": [
                {"data": "providers"},
                {"data": "makers"},
                {"data": "chip"},
                {"data": "percentages"},
                {"data": "benefits"},
                {"data": "consumeds"},
                {"data": "balances"},
                {"data": "dates"}
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');
            let provider = $('#change_provider').val();
            let maker = $('#maker').val();
            let currency = $('#currency').val();
            let chips = $('#chips').val();
            let startDate = $('#start_date').val();
            let endDate = $('#end_date').val();
            let percentage = $('#percentage').val();
            let route = `${$table.data('route')}/${startDate}/${endDate}?provider=${provider}&maker=${maker}&currency=${currency}&chips=${chips}&percentage=${percentage}`;
            console.log(route)
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    }


}

window.FinancialReport = FinancialReport;
