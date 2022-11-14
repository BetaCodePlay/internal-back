import {initDatepickerStartToday, initFileInput, initSelect2, initTinyMCE} from "./commons";
import {swalConfirm, swalError, swalSuccessNoButton, swalSuccessWithButton} from "../../commons/js/core";

class Notifications {
    // All notifications
    all() {
        let $table = $('#notifications-table');
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.notifications"
            },
            "order": [],
            "columns": [
                {"data": "image"},
                {"data": "title"},
                {"data": "language"},
                {"data": "currency_iso"},
                {"data": "date"},
                {"data": "status", "className": "text-right"},
                {"data": "actions", "className": "text-right"}
            ],
            "initComplete": function () {
                let api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
                Notifications.listUsers()
                $(document).on('click', '.delete', function () {
                    let $button = $(this);
                    swalConfirm($button.data('route'), function () {
                        $table.DataTable().ajax.url($table.data('route')).load();
                    });
                });
            }
        });
    }

    // All notifications groups
    allGroups() {
        let $table = $('#notifications-groups-table');
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.groups"
            },
            "order": [],
            "columns": [
                {"data": "name"},
                {"data": "description"},
                {"data": "currency_iso"},
                {"data": "operator"},
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

    // Assing user to group
    assignUserGroup() {
        let $button = $('#store');
        let $form = $('#assign-user-form');
        let $table = $('#group-user-table');

        $form.on('submit', function (event) {
            event.preventDefault();
            $button.button('loading');
            let formData = new FormData(this);

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
                $table.DataTable().ajax.url($table.data('route')).load();
                swalSuccessWithButton(json);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }

    // Group users
    groupUsers() {
        let $table = $('#group-user-table');
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.notifications"
            },
            "order": [],
            "columns": [
                {"data": "user_id"},
                {"data": "username"},
                {"data": "email"},
                {"data": "first_name"},
                {"data": "last_name"},
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

    //list users
    static listUsers() {
        let $modal = $('#list-users');
        $modal.on('show.bs.modal', function(event) {
            let $target = $(event.relatedTarget);
            let $route = $target.data('route')
            let $table = $('#list-users-table');

            if ($.fn.DataTable.isDataTable('#list-users-table')) {
                $table.DataTable().destroy();
            }

            $table.DataTable({
                "ajax": {
                    "url": $route,
                    "dataSrc": "data.users"
                },
                "order": [],
                "columns": [
                    {"data": "user"},
                    {"data": "username"},
                ],
                "initComplete": function () {
                    let api = this.api();
                    api.buttons().container()
                        .appendTo($('#table-buttons'));
                }
            });
        })
    }

    // Store
    store() {
        initDatepickerStartToday();
        initFileInput();
        initSelect2();
        initTinyMCE();

        let $button = $('#store');
        let $form = $('#notifications-form');

        $form.on('submit', function (event) {
            event.preventDefault();
            tinymce.triggerSave();
            let formData = new FormData(this);

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

    // Store group
    storeGroup() {
        initDatepickerStartToday();
        initFileInput();
        initSelect2();
        initTinyMCE();

        let $button = $('#store');
        let $form = $('#notifications-groups-form');

        $form.on('submit', function (event) {
            event.preventDefault();
            tinymce.triggerSave();
            let formData = new FormData(this);

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

    // Type notifications
    typeNotification(){
        $('#type').change(function () {
            let type = $(this).val();
            switch(type) {
                case '1':
                    $('.search-user').removeClass('d-none');
                    $('.search-user').addClass('form-group');
                    $('.groups').addClass('d-none');
                    $('.segment').addClass('d-none');
                    $('.excel').addClass('d-none');
                    break;
                case '2':
                    $('.groups').removeClass('d-none');
                    $('.groups').addClass('form-group');
                    $('.search-user').addClass('d-none');
                    $('.segment').addClass('d-none');
                    $('.excel').addClass('d-none');
                    break;
                case '3':
                    $('.groups').addClass('d-none');
                    $('.search-user').addClass('d-none');
                    $('.segment').addClass('d-none');
                    $('.excel').addClass('d-none');
                    break;
                case '4':
                    //$('.groups').addClass('d-none');
                    //$('.search-user').addClass('d-none');
                    //$('.segment').removeClass('d-none');
                    //$('.excel').addClass('d-none');
                    break;
                case '5':
                    $('.groups').addClass('d-none');
                    $('.search-user').addClass('d-none');
                    $('.segment').addClass('d-none');
                    $('.excel').removeClass('d-none');
                    break;
                default:
                    $('.search-user').addClass('d-none');
                    $('.groups').addClass('d-none');
                    $('.segment').addClass('d-none');
                    $('.excel').addClass('d-none');
                    break;
            }
        });
    };

    // Type notifications
    typeNotificationEdit(type) {
        switch (type) {
            case 1:
                $('.search-user').removeClass('d-none');
                $('.search-user').addClass('form-group');
                $('.groups').addClass('d-none');
                $('.segment').addClass('d-none');
                break;
            case 2:
                $('.groups').removeClass('d-none');
                $('.groups').addClass('form-group');
                $('.search-user').addClass('d-none');
                $('.segment').addClass('d-none');
                break;
            case 3:
                $('.groups').addClass('d-none');
                $('.search-user').addClass('d-none');
                $('.segment').addClass('d-none');
                break;
            case 4:
                $('.groups').addClass('d-none');
                $('.search-user').addClass('d-none');
                $('.segment').addClass('d-none');
                break;
            default:
                $('.search-user').addClass('d-none');
                $('.groups').addClass('d-none');
                $('.segment').addClass('d-none');
                break;
        }
    };

    // Update
    update(preview) {
        initDatepickerStartToday();
        initFileInput(preview);
        initSelect2();
        initTinyMCE();

        let $button = $('#update');
        let $form = $('#notifications-form');
        let $table = $('#notifications-users-table');
        $form.on('submit', function (event) {
            event.preventDefault();
            tinymce.triggerSave();
            let formData = new FormData(this);

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
                $table.DataTable().ajax.url($table.data('route')).load();

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }

    // Update group
    updateGroup() {
        initDatepickerStartToday();
        initSelect2();

        let $button = $('#update');
        let $form = $('#notifications-groups-form');

        $form.on('submit', function (event) {
            event.preventDefault();
            let formData = new FormData(this);

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
                swalSuccessWithButton(json);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }

    // Users notificacion
    usersNotificacion(){
        let $table = $('#notifications-users-table');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.users"
            },
            "order": [],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "actions", "className": "text-right"}
            ],
            "initComplete": function () {
                let api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
                $(document).on('click', '.remove', function () {
                    let $button = $(this);
                    swalConfirm($button.data('route'), function () {
                        $table.DataTable().ajax.url($table.data('route')).load();
                    });
                });
            }
        });
    }
}

window.Notifications = Notifications;
