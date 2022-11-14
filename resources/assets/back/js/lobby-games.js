import {getCookie, initDatepickerStartToday, initFileInput, initSelect2} from "./commons";
import {swalConfirm, swalError, swalSuccessWithButton} from "../../commons/js/core";

class LobbyGames {

    // All lobby-games
    all(){
        let $table = $('#lobby-games-table');
        let api;
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.games"
            },
            "order": [[0, "asc"]],
            "columns": [
                {"data": "descriptions"},
                {"data": "start"},
                {"data": "actions", "className": "text-right"},
            ],
            "initComplete": function () {
                api = this.api()
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
    };

     game(){
         initSelect2();

         $('#change-provider').on('change', function()
         {
             let provider = $(this).val();
             let route = $(this).data('route');
             var games = $('#games');
             console.log('route', route);
             console.log('provider', provider);
             games.select2({
                 minimumInputLength: 3 // only start searching when the user has input 3 or more characters
             });
             if(provider !== '') {
                 $.ajax({
                     url: route,
                     type: 'post',
                     dataType: 'json',
                     data: {
                         provider: provider
                     }
                 }).done(function (json) {
                     games.html('loading');
                     console.log('respuesta', json);
                     games.html(json.data.games);
                     $(json.data.games).each(function(i, v){
                         games.append('<option value="' + v.id + '">' + v.description + '</option>');
                     })
                     games.prop('disabled', false);
                 }).fail(function (json) {
                     swalError(json);
                 });
             }else{
                 games.val('');
                 games.prop('disabled', true);
             }
         });
     }

    // Change status
    static change(route) {
        $('.change-whitelabels').change(function () {
            let status = $(this).val();
            let whitelabel = $(this).data('whitelabel');
            $.ajax({
                url: route,
                type: 'post',
                dataType: 'json',
            }).done(function (json) {
                swalSuccessWithButton(json);

            }).fail(function (json) {
                swalError(json);
            });
        });
    }

    // lock lobby
    static lockLobby(){
        let $table = $('#lobby-games-table');
        let api;
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.games"
            },
            "order": [[0, "asc"]],
            "columns": [
                {"data": "descriptions"},
                {"data": "start"},
                {"data": "actions", "className": "text-right"},
            ],
            "initComplete": function () {
                api = this.api()
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
    };

    // Registration lobby games
    save() {
        initSelect2();
        let $form = $('#save-form');
        let $button = $('#save');
        $button.click(function () {
            $button.button('loading');
            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                dataType: 'json',
                data:  $('#save-form').serialize()
            }).done(function (json) {
                $('save-form').trigger('reset');
                $('form select').val(null).trigger('change');
                swalSuccessWithButton(json);
                LobbyGames.lockLobby();
            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }
}

window.LobbyGames = LobbyGames;
