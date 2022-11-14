import {initDatepickerStartToday, initSelect2, initDateTimePicker} from "./commons";
import {swalConfirm, swalError, swalSuccessWithButton} from "../../commons/js/core";

class MarketingCampaigns {
    // All campaigns
    all() {
        let $table = $('#marketing-campaigns-table');
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.campaigns"
            },
            "order": [],
            "columns": [
                {"data": "title"},
                {"data": "segment"},
                {"data": "email_title"},
                {"data": "language"},
                {"data": "currency_iso"},
                {"data": "date", "className": "text-right"},
                {"data": "status", "className": "text-right"},
                {"data": "actions", "className": "text-right"}
            ],
            "initComplete": function () {
                let api = this.api();
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
    }

    // Store
    store() {
        initSelect2();
        initDateTimePicker()

        let $button = $('#store');
        let $form = $('#marketing-campaigns-form');

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
                //$('form select').val(null).trigger('change');
                $('#email_template, #segment, #currency, #language').val(null).trigger('change');
                swalSuccessWithButton(json);

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
        initDateTimePicker()

        let $button = $('#update');
        let $form = $('#marketing-campaigns-form');

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
                swalSuccessWithButton(json);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }
}

window.MarketingCampaigns = MarketingCampaigns;
