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
                {"data": "maker"},
                {"data": "category"},
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
            let game = $('#games').val();
            console.log('paso', image, provider, menu, filter);
            let route = `${$table.data('route')}?provider=${provider}&route=${menu}&game=${game}&image=${image}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    };

    // Games Dotsuite
    game() {
        initSelect2();
        $('#provider').on('change', function(){
            let provider = $('#provider').val();
            let route = $(this).data('route');
            let games = $('#games');

            if(provider !== '') {
                $.ajax({
                    url: route,
                    type: 'get',
                    dataType: 'json',
                    data: {
                        provider: provider
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

    // get providers
    providersByMaker(){
        initSelect2();
        $('#maker').on('change', function () {
            let maker = $(this).val();
            let providers = $('#provider');
            let route = $(this).data('route');
            if(maker !== '') {
                $.ajax({
                    url: route,
                    type: 'get',
                    dataType: 'json',
                    data: {
                        maker
                    }
                }).done(function (json) {
                    $('#provider option[value!=""]').remove();
                    $(json.data.providers).each(function (key, element) {
                        providers.append("<option value=" + element.id + ">" + element.name + "</option>");
                    })
                    providers.prop('disabled', false);
                }).fail(function (json) {});
            }else{
                providers.val('');
            }
        }).trigger('change');
    }

    // show product id
    products(){
        $('#provider').on('change', function () {
            var provider = $("#provider").val();
            if (provider == 171) {
                $(".div_a_product_id").fadeIn("200")
            } else {
                $(".div_a_product_id").fadeOut("200")
            }
        }).trigger('change');
    }

    //get categories
    categoryByMaker(){
        initSelect2();
        $('#maker').on('change', function () {
            let maker = $(this).val();
            let categories = $('#category');
            let route = $(this).data('categories');
            if(maker !== '') {
                $.ajax({
                    url: route,
                    type: 'get',
                    dataType: 'json',
                    data: {
                        maker
                    }
                }).done(function (json) {
                    $('#category option[value!=""]').remove();
                    $(json.data.categories).each(function (key, element) {
                        categories.append("<option value=" + element.category + ">" + element.category + "</option>");
                    })
                    categories.prop('disabled', false);
                }).fail(function (json) {});
            }else{
                categories.val('');
            }
        }).trigger('change');
    }

    //Select games
    gamesByCategory() {
        initSelect2();
        $('#category').on('change', function(){
            let provider = $('#provider').val();
            let product = $('#product_id').val();
            let category = $('#category').val();
            let maker = $('#maker').val();
            let games = $('#games');
            let route =  $('#category').data('route');
            if(category !== '') {
                $.ajax({
                    url: route,
                    type: 'get',
                    dataType: 'json',
                    data: {
                        category,
                        product,
                        maker,
                        provider
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

    gamesByProducts(){
        $('#product_id').on('change', function(){
            let provider = $('#provider').val();
            let product = $('#product_id').val();
            let category = $('#category').val();
            let maker = $('#maker').val();
            let route =  $('#category').data('route');
            let games = $('#games');
            if(product !== '') {
                $.ajax({
                    url: route,
                    type: 'get',
                    dataType: 'json',
                    data: {
                        category,
                        product,
                        maker,
                        provider
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
}

window.LobbyGames = LobbyGames;
