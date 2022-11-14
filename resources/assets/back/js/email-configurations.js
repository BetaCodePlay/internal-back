import {swalError, swalSuccessWithButton} from "../../commons/js/core";
import {initTinyMCE} from "./commons";

class EmailConfigurations {

    // Email configurations
    emailConfigurations() {
        let $table = $('#email-configurations-table');
        let $button = $('#update');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.email"
            },
            "order": [[1, 'asc']],
            "columns": [
                {"data": "name"},
                {"data": "email", "className": "text-right"},
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');
            let route = `${$table.data('route')}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    };

    // Update Email
    updateEmail() {
        initTinyMCE();
        let $button = $('#update-email');
        let $form = $('#email-form');

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

window.EmailConfigurations = EmailConfigurations;
