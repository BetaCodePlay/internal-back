import {initFileInput, initSelect2} from "./commons";
import {swalConfirm, swalError, swalSuccessWithButton} from "../../commons/js/core";

class SectionModals {
    // All modals
    all() {
        let $table = $('#modals-table');
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.modals"
            },
            "order": [],
            "columns": [
                {"data": "image"},
                {"data": "route"},
                {"data": "one_time"},
                {"data": "scroll"},
                {"data": "language"},
                {"data": "currency_iso"},
                {"data": "status", "className": "text-right"},
                {"data": "actions", "className": "text-right"},
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
        initFileInput();
        initSelect2();

        var $button = $('#store');
        var $form = $('#modals-form');

        $form.on('submit', function (event) {
            event.preventDefault();
            var formData = new FormData(this);

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
                $('form select').val(null).trigger('change');
                swalSuccessWithButton(json);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }

    // Update
    update(preview) {
        initFileInput(preview);
        initSelect2();

        var $button = $('#update');
        var $form = $('#modals-form');

        $form.on('submit', function (event) {
            event.preventDefault();
            var formData = new FormData(this);

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
                $('#file').val(json.data.file);
                swalSuccessWithButton(json);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }
}

window.SectionModals = SectionModals;
