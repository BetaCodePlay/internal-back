import {initDateRangePickerEndToday, initSelect2, initDatepickerEndToday, clearForm, initFileInput} from "./commons";
import {swalConfirm, swalError, swalSuccessWithButton, swalSuccessNoButton} from "../../commons/js/core";

class Segments {

    // Add user segments
    addUser() {
        initSelect2();
        let $button = $('#add-user');
        let $form = $('#segment-user-form');
        $button.click(function () {
            $button.button('loading');

            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                data: $form.serialize()

            }).done(function (json) {
                $('#add-segmentations-modal').modal('hide');
                $('#segments').val('').trigger('change');
                swalSuccessNoButton(json);
                $form.trigger('reset');
                setTimeout(() => {
                    window.location.href = json.data.route;
                }, 500)

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }

    // All segments
    all() {
        let $table = $('#segments-table');
        let api;
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.segments"
            },
            "order": [],
            "columns": [
                {"data": "name"},
                {"data": "description"},
                {"data": "quantity", "className": "text-right"},
                {"data": "actions", "className": "text-right"},
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
                $(document).on('click', '.delete', function () {
                    let $button = $(this);
                    swalConfirm($button.data('route'), function () {
                        $table.DataTable().ajax.url($table.data('route')).load();
                    });
                });
                $(document).on('click', '.disable', function () {
                    let $button = $(this);
                    console.log($button.data('route'))
                    /*swalConfirm($button.data('route'), function () {
                        $table.DataTable().ajax.url($table.data('route')).load();
                    });*/
                });
            }
        });
    }

    // All segments
    allUser() {
        let $table = $('#segments-user-table');
        let api;
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.segments"
            },
            "order": [],
            "columns": [
                {"data": "name"},
                {"data": "actions", "className": "text-right"},
            ],
            "initComplete": function () {
                api = this.api();
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

    // List user
    listUser() {
        let $table = $('#users-segment-table');
        let api;

        if ($.fn.DataTable.isDataTable('#users-segment-table')) {
            $table.DataTable().destroy();
        }

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.users",
            },
            "order": [],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "actions", "className": "text-right"},
            ],
            "initComplete": function () {
                api = this.api();
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

    // Store segment
    store() {
        initSelect2();
        let $button = $('#store');
        let $form = $('#segments-form');
        $button.click(function () {
            $button.button('loading');

            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                data: $form.serialize()

            }).done(function (json) {
                $('#store-segments-modal').modal('hide');
                swalSuccessWithButton(json);
                $form.trigger('reset');

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }

    // Update segment
    update() {
        initSelect2();
        let $button = $('#store');
        let $form = $('#segments-form');
        $button.click(function () {
            $button.button('loading');

            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                data: $form.serialize()

            }).done(function (json) {
                swalSuccessWithButton(json);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }

    // Users data
    usersData() {
        initSelect2();
        initDateRangePickerEndToday(open = 'right');
        initDatepickerEndToday();

        let api;
        let $table = $('#segmentation-table');
        let $button = $('#search');
        let $form = $('#segmentation-form');
        clearForm($form);

        $table.DataTable({
            "ajax": {
                "url": $form.attr('action'),
                "dataSrc": "data.users",
            },
            "order": [],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "full_name"},
                {"data": "email"},
                {"data": "phone"},
                {"data": "country"},
                {"data": "currency_iso"},
                {"data": "deposit", "className": "text-right", "type": "num-fmt"},
                {"data": "withdrawal", "className": "text-right", "type": "num-fmt"},
                {"data": "login", "className": "text-right"},
                {"data": "profile", "className": "text-right"},
                {"data": "created", "className": "text-right"},
                {"data": "language", "className": "text-right"},
                {"data": "deposits", "className": "text-right", "type": "num-fmt"},
                {"data": "played", "className": "text-right", "type": "num-fmt"},
                {"data": "balance", "className": "text-right", "type": "num-fmt"},
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');
            let route = $form.attr('action') + '?' + $form.serialize();
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });


        $table.on('xhr.dt', function (event, settings, json, xhr) {
            let $filter = JSON.stringify(json.data.filter);
            $('#filter').val($filter);

            if (json.data.users.length > 0) {
                $('#segments').removeClass('d-none');
                $('#create-segment').addClass('d-none');
                $('#users').val(json.data.ids);
            }

            if (xhr.status === 500 || xhr.status === 422) {
                swalError(xhr);
                $button.button('reset');
            }
        });

        $form.keypress(function (event) {
            if (event.keyCode === 13) {
                $button.click();
            }
        });
    }

}

window.Segments = Segments;
