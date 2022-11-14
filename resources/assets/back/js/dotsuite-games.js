import {swalConfirm, swalError, swalSuccessNoButton} from "../../commons/js/core";
import {initSelect2} from "./commons";

class DotSuiteGames {

    // All DotSuiteGames
    all() {
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
                {"data": "route"},
                {"data": "order", "className": "text-right"},
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
            let route = `${$table.data('route')}?provider=${provider}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    };

    // Games Dotsuite
    game() {
        initSelect2();
        $('#change_provider').on('change', function () {
            let provider = $('#change_provider').val();
            let route = $(this).data('route');
            let games = $('#games');

            if (provider !== '') {
                $.ajax({
                    url: route,
                    type: 'get',
                    dataType: 'json',
                    data: {
                        change_provider: provider
                    }
                }).done(function (json) {
                    games.html('loading');
                    games.html(json.data.games);
                    $(json.data.games).each(function (key, element) {
                        games.append("<option value=" + element.id + ">" + element.description + "</option>");
                    })
                    games.prop('disabled', false);
                }).fail(function (json) {

                });
            } else {
                games.val('');
            }
        });
    }

    //store dotsuite
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
                data: $form.serialize()

            }).done(function (json) {
                $('store-form').trigger('reset');
                $('select').val(null).trigger('change');
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

window.DotSuiteGames = DotSuiteGames;
