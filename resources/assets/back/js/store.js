import {
    clearForm,
    getCookie,
    initDatepickerStartToday, initDateRangePickerEndToday,
    initDateTimePicker,
    initFileInput,
    initSelect2,
    initTinyMCE
} from "./commons";
import {swalConfirm, swalError, swalSuccessWithButton, swalSuccessNoButton} from "../../commons/js/core";

class Store {

    // All posts
    all() {
        let $table = $('#rewards-table');
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.rewards"
            },
            "order": [],
            "columns": [
                {"data": "image"},
                {"data": "name"},
                {"data": "dates", "className": "text-right"},
                {"data": "language"},
                {"data": "currency_iso"},
                {"data": "category_name"},
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

    // All actions configurations
    allActionsConfigurations() {
        let $table = $('#actions-configurations-table');
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.actions"
            },
            "order": [],
            "columns": [
                {"data": "name"},
                {"data": "actions", "className": "text-right"}
            ],
            "initComplete": function () {
                let api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });
    }

    //Actions form type
    actionsFormType()
    {
        if ($('#action').val() !== '') {
            const action =$('#action').val();
            if( $('#provider_type').val() == '') {
                $('#currency').trigger('change');
            }

            if (action == 1 || action == 3) {
                $('#desktop').removeClass('d-none');
                $('#mobile').removeClass('d-none');
            } else {
                $('#desktop').addClass('d-none');
                $('#mobile').addClass('d-none');
            }
        } else {
            $('#action').change(function () {
                const action = $(this).val();
                if (action == 1 || action == 3) {
                    $('#desktop').removeClass('d-none');
                    $('#mobile').removeClass('d-none');
                } else {
                    $('#desktop').addClass('d-none');
                    $('#mobile').addClass('d-none');
                }
            });
        }
    }

    // categories
    categories(){
        let $table = $('#categories-table');
        let api;
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.categories"
            },
            "order": [[0, "asc"]],
            "columns": [
                {"data": "client"},
                {"data": "name"},
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
    };

    //Exclude provider
    excludeProvider() {
        initSelect2();
        $('#provider_type').on('change', function () {
            let provider_type = $(this).val();
            let route = $(this).data('route');
            let $excludeProvider = $('#exclude_provider');

            if (provider_type !== '') {
                $.ajax({
                    url: route,
                    type: 'get',
                    dataType: 'json',
                    data: {
                        provider_type
                    }
                }).done(function (json) {
                    $(json.data.exclude_providers).each(function (key, element) {
                        $excludeProvider.append("<option value=" + element.id + ">" + element.name + "</option>");
                    })
                    $excludeProvider.prop('disabled', false);
                }).fail(function (json) {

                });
            } else {
                $excludeProvider.val('');
            }
        });
    }

    //Redeemed rewards
    redeemedRewards() {
        initDateRangePickerEndToday(open = 'right');
        let $table = $('#redeemed-rewards-table');
        let $button = $('#search');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.rewards"
            },
            "order": [[0, 'asc']],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "name"},
                {"data": "currency_iso"},
                {"data": "amount", "className": "text-right"},
                {"data": "date", "className": "text-right"},
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');
            let currency = $('#currency').val();
            let startDate = $('#start_date').val();
            let endDate = $('#end_date').val();
            let route = `${$table.data('route')}?start_date=${startDate}&end_date=${endDate}&currency=${currency}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    }

    // Registration category
    saveCategory() {
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
                swalSuccessWithButton(json);
            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }

    // Type providers
    typeProviders() {
        initSelect2();
        let $currency = $('#currency');

        $currency.on('change', function () {
            let currency = $(this).val();
            let route = $(this).data('route');
            let $provider_type = $('#provider_type');

            if (currency !== '') {
                $.ajax({
                    url: route,
                    type: 'get',
                    dataType: 'json',
                    data: {
                        currency
                    }
                }).done(function (json) {
                    $provider_type.val(null).trigger('change');
                    $(json.data.type_providers).each(function (key, element) {
                        $provider_type.append("<option value=" + element.id + ">" + element.name + "</option>");
                    })
                    $provider_type.prop('disabled', false);
                }).fail(function (json) {

                });
            } else {
                $provider_type.val('');
            }
        });
    }

    // Store
    store() {
        initDatepickerStartToday();
        initFileInput();
        initSelect2();
        initTinyMCE();

        var $button = $('#store');
        var $form = $('#rewards-form');

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

    // Store actions configurations
    storeActionsConfigurations() {
        initDateTimePicker()
        initSelect2();
        var $button = $('#store');
        var $form = $('#actions-configurations-form');

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
                swalSuccessWithButton(json);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }

    // Update
    update(preview) {
        initDatepickerStartToday();
        initFileInput(preview);
        initSelect2();
        initTinyMCE();

        var $button = $('#update');
        var $form = $('#reward-form');

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

    // Update action configuration
    updateActionConfiguration() {
        initDateTimePicker()
        initSelect2();
        var $button = $('#update');
        var $form = $('#actions-configurations-form');

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
                swalSuccessNoButton(json);
                setTimeout(() => {
                    window.location.href = json.data.route;
                }, 1000)

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }

    // Update category
    updateCategory() {
        let $form = $('#posts-form');
        let $button = $('#update');

        $button.click(function () {
            $button.button('loading');
            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                dataType: 'json',
                data: $('#posts-form').serialize()

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

window.Store = Store;
