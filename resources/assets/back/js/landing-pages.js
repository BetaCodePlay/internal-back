import {swalConfirm, swalError, swalSuccessWithButton} from "../../commons/js/core";
import {initDatepickerStartToday, initFileInput, initSelect2, initTinyMCE} from "./commons";

class LandingPages {
    // All images
    all() {
        let $table = $('#landing-pages-table');
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.images"
            },
            "order": [],
            "columns": [
                {"data": "name", "className": "text-left" },
                {"data": "dates", "className": "text-right"},
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
        initDatepickerStartToday();
        initFileInput();
        initSelect2();
        initTinyMCE();

        var $button = $('#store');
        var $form = $('#landing-form');

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
    update() {
        initDatepickerStartToday();
        initFileInput();
        initSelect2();
        initTinyMCE();
        var $button = $('#store');
        var $form = $('#landing-form');
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
                data: {
                    data: formData,
                    additional_content: tinymce.get('additional_content').getContent(),
                    steps_content: tinymce.get('steps_content').getContent(),
                    terms_content: tinymce.get('terms_content').getContent(),
                },

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
}

window.LandingPages = LandingPages;
