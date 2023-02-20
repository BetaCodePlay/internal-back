import {clearForm, initFileInput, initSelect2} from "./commons";
import {swalConfirm, swalError, swalSuccessNoButton, swalSuccessWithButton} from "../../commons/js/core";

class LobbyGames {

    // All DotSuiteGames
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
                {"data": "image"},
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
            let image = $('#image').val();
            let provider = $('#provider').val();
            let menu = $('#menu').val();
            let filter = $('#filter').val();
            let route = `${$table.data('route')}?provider=${provider}&route=${menu}&game=${filter}&image=${image}`;
            console.log('route', route);
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    };

    // Games Dotsuite
    game() {
        initSelect2();
        $('#change_provider').on('change', function(){
            let provider = $('#change_provider').val();
            let route = $(this).data('route');
            let games = $('#games');

            if(provider !== '') {
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

    //sort
    sort(preview) {
        initSelect2();
        initFileInput(preview);

        let $form = $('#store-form');
        let $button = $('#update');

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

    //store
    store() {
        initFileInput();
        initSelect2();

        let $form = $('#store-form');
        let $forms = $('#filter-form');
        let $button = $('#store');
        let $table = $('#games-table');
        clearForm($forms);

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
                $('store-form').trigger('reset');
                $('filter-form').trigger('reset');
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

window.LobbyGames = LobbyGames;
