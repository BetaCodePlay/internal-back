import {getCookie, initDatepickerStartToday, initFileInput, initSelect2} from "./commons";
import {swalConfirm, swalError, swalSuccessWithButton} from "../../commons/js/core";

class SectionGames {
    // All games
    all() {
        let $table = $('#games-table');
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.games"
            },
            "order": [],
            "columns": [
                {"data": "description"},
                {"data": "additional_info"},
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
        initSelect2();
        let $form = $('#games-form');
        let $button = $('#save');
        $button.click(function () {
             $button.button('loading');
             $.ajax({
                 url: $form.attr('action'),
                 method: 'post',
                 dataType: 'json',
                 data:  $('#games-form').serialize()
            }).done(function (json) {
                $('#games-form').trigger('reset');
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
    let $form = $('#games-form');
    let $button = $('#update');

    $button.click(function () {
        $button.button('loading');
        $.ajax({
            url: $form.attr('action'),
            method: 'post',
            dataType: 'json',
            data: $('#games-form').serialize()

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

window.SectionGames = SectionGames;
