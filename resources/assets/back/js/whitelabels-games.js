import {swalConfirm, swalError, swalSuccessNoButton, swalSuccessWithButton} from "../../commons/js/core";
import {clearForm, initSelect2} from "./commons";
import moment from "moment";

class WhitelabelsGames {

    // All Whitelabels-games
    all(){
        let $table = $('#games-table');
        let $button = $('#search');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.games"
            },
            "order": [[0, "asc"]],
            "columns": [
                {"data": "provider"},
                {"data": "game"},
                {"data": "category"},
                {"data": "device"},
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

        $button.click(function () {
            $button.button('loading');
            let provider = $('#provider').val();
            let category = $('#category').val();
            let route = `${$table.data('route')}?provider=${provider}&category=${category}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    };

    // Games
    game() {
        initSelect2();
        $('#change_provider, #devices').on('change', function(){
            let provider = $('#change_provider').val();
            let devices = $("#devices").val();
            let route = $(this).data('route');
            let games = $('#games');

            if(provider !== '') {
                $.ajax({
                    url: route,
                    type: 'get',
                    dataType: 'json',
                    data: {
                        change_provider: provider,
                        devices: devices
                    }
                }).done(function (json) {
                    games.html('loading');
                    games.html(json.data.games);
                    $(json.data.games).each(function(key, element){
                        games.append("<option value=" + element.id + ">" + element.description + "</option>");
                    })
                    games.prop('disabled', false);
                }).fail(function (json) {

                });
            }else{
                games.val('');
            }
        });
    }

    //store
    store() {
        initSelect2();
        let $form = $('#store-form');
        let $button = $('#store');
        let $table = $('#games-table');

        $button.click(function () {
            $button.button('loading');

            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                dataType: 'json',
                data:  $form .serialize()

            }).done(function (json) {
                $('store-form').trigger('reset');
                $('form select').val(null).trigger('change');
                $table.DataTable().ajax.url($table.data('route')).load();
                swalSuccessNoButton(json);
                setTimeout(() => window.location.href = json.data.route, 1000);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }

}

window.WhitelabelsGames = WhitelabelsGames;
