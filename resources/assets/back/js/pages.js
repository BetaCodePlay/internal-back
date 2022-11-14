import {initSelect2, initTinyMCE} from "./commons";
import {swalError, swalSuccessWithButton} from "../../commons/js/core";

class Pages {

    // All sliders
    all() {
        let $table = $('#pages-table');
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.pages"
            },
            "order": [[0, "asc"]],
            "columns": [
                {"data": "original_title"},
                {"data": "title"},
                {"data": "language"},
                {"data": "updated", "className": "text-right"},
                {"data": "status", "className": "text-right"},
                {"data": "actions", "className": "text-right"},
            ],
            "initComplete": function () {
                let api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });
    }

    // Update pages
    update() {
        initTinyMCE();
        initSelect2();
        let $form = $('#posts-form');
        let $button = $('#update');

        $button.click(function () {
            $button.button('loading');
            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                dataType: 'json',
                data: {
                    id: $('#id').val(),
                    title: $('#title').val(),
                    status: $('#status').val(),
                    content: tinymce.get('content').getContent()
                },

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

window.Pages = Pages;
