import {initDatepickerStartToday, initDateTimePicker, initFileInput, initSelect2} from "./commons";
import {swalConfirm, swalError, swalSuccessWithButton} from "../../commons/js/core";
import moment from 'moment';
import i18next from 'i18next';
import Backend from 'i18next-http-backend';

class Sliders {
    // All sliders
    all() {
        let $table = $('#sliders-table');
        let $button = $('#update');
        let api;
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.sliders"
            },
            "order": [[6, "asc"]],
            "columns": [
                {"data": "image"},
                {"data": "front"},
                {"data": "route"},
                {"data": "dates", "className": "text-right"},
                {"data": "language"},
                {"data": "currency_iso"},
                {"data": "mobile"},
                {"data": "order","className": "text-right"},
                {"data": "status", "className": "text-right"},
                {"data": "actions", "className": "text-right"},
            ],
            "initComplete": function () {
                api = this.api();
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
            let templateElementType = $('#template_element_type').val();
            let section = $('#section').val();
            let status = $('#status').val();
            let device = $('#device').val();
            let currency = $('#currency').val();
            let language = $('#language').val();
            let routes = $('#routes').val();
            let route = `${$table.data('route')}?templateElementType=${templateElementType}&section=${section}&status=${status}&device=${device}&currency=${currency}&language=${language}&routes=${routes}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    }

    // Store
    store() {
        initDateTimePicker()
        initFileInput();
        initSelect2();

        let $button = $('#store');
        let $form = $('#sliders-form');
        let $file = $('#image');
        let $file1 = $('#front');
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
                $('#route, #status, #currency, #language, #device').val(null).trigger('change');
                swalSuccessWithButton(json);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }

    // Update
    update(preview, preview2) {
        initFileInput(preview, preview2);
        initSelect2();
        initDateTimePicker();
        console.log('preview', preview, preview2);
        var $button = $('#update');
        var $form = $('#sliders-form');

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
                $('#file, #file1').val(json.data.file);
                console.log('file', #file);
                swalSuccessWithButton(json);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }
}

window.Sliders = Sliders;
