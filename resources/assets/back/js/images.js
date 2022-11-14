import {getCookie, initDatepickerStartToday, initFileInput, initSelect2} from "./commons";
import {swalConfirm, swalError, swalSuccessWithButton} from "../../commons/js/core";

class Images {

    // All sliders
    all() {
        let $table = $('#images-table');
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.sliders"
            },
            "order": [],
            "columns": [
                {"data": "image"},
                {"data": "dates", "className": "text-right"},
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
        initDatepickerStartToday();
        initFileInput();
        initSelect2();

        var $button = $('#store');
        var $form = $('#images-form');

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
        initDatepickerStartToday();
        initFileInput(preview);
        initSelect2();

        var $button = $('#update');
        var $form = $('#images-form');

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

window.Images = Images;
