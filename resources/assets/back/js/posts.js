import {initDatepickerStartToday, initFileInput, initSelect2, initTinyMCE} from "./commons";
import {swalConfirm, swalError, swalSuccessWithButton} from "../../commons/js/core";

class Posts {
    // All posts
    all() {
        let $table = $('#posts-table');
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.posts"
            },
            "order": [],
            "columns": [
                {"data": "image"},
                {"data": "title"},
                {"data": "dates", "className": "text-right"},
                {"data": "language"},
                {"data": "currency_iso"},
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
        initDatepickerStartToday();
        initFileInput();
        initSelect2();
        initTinyMCE();

        let $button = $('#store');
        let $form = $('#posts-form');

        $form.on('submit', function (event) {
            event.preventDefault();
            tinymce.triggerSave();
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
    update(coverImage, mainImage) {
        initDatepickerStartToday();
        initSelect2();
        initTinyMCE();
        initFileInput(coverImage, 'image');

        if (mainImage !== '') {
            initFileInput(mainImage, 'main_image');
        } else {
            initFileInput(undefined, 'main_image');
        }

        var $button = $('#update');
        var $form = $('#posts-form');

        $form.on('submit', function (event) {
            event.preventDefault();
            tinymce.triggerSave();
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

window.Posts = Posts;
