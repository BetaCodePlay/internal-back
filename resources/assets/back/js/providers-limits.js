import {swalError, swalSuccessWithButton} from "../../commons/js/core";

class ProvidersLimits {

    // All sliders
    all() {
        let $table = $('#providers-limits-table');
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.limits"
            },
            "order": [],
            "columns": [
                {"data": "description"},
                {"data": "currency_iso"},
                {"data": "limits"},
                {"data": "created", "className": "text-right"},
                {"data": "updated", "className": "text-right"},
                {"data": "actions", "className": "text-right"},
            ],
            "initComplete": function () {
                let api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });
    }

    // Store
    store() {
        var $button = $('#store');
        var $form = $('#providers-limits-form');

        $button.click(function () {
            $button.button('loading');
            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                dataType: 'json',
                data: $form.serialize(),

            }).done(function (json) {
                $form.trigger('reset');
                $('form select').val(null).trigger('change');
                swalSuccessWithButton(json);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }

    // Update pages
    update() {
        let $form = $('#providers-limits-form');
        let $button = $('#update');

        $button.click(function () {
            $button.button('loading');
            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                dataType: 'json',
                data: $form.serialize(),

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

window.ProductsLimits = ProvidersLimits;
