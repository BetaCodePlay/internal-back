import {swalConfirm, swalError, swalSuccessWithButton, swalSuccessNoButton, swalInput} from "../../commons/js/core";
import {clearForm, initSelect2} from "./commons";

class Referrals {

    // Add referral user
    addReferral() {
        initSelect2();
        let $button = $('#create');

        $button.click(function () {
            let $table = $('#referral-users-list-table');
            let $form = $('#add-referral-user-form');
            $button.button('loading');
            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                dataType: 'json',
                data: $form.serialize()
            }).done(function (json) {
                $('#user').val(null).trigger('change');
                $('#user_refer').val(null).trigger('change');
                $form.trigger('reset');
                swalSuccessWithButton(json);
            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });

        $('#add-referral-user-form').keypress(function (event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                $button.click();
            }
        });
    }

    // Get referral users list
    referralUsersList() {
        initSelect2();
        let $table = $('#referral-users-list-table');
        let $button = $('#search');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.users"
            },
            "order": [[0, 'asc']],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "email"},
                {"data": "currency"},
                {"data": "referral"},
                {"data": "date"},
                {"data": "actions", "className": "text-right"},
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));

                $(document).on('click', '.remove-referral', function () {
                    let $button = $(this);
                    let route;
                    if ($('.referral').val() !== '') {
                        let user = $('#user').val();
                        let currency = $('#currency').val();
                        route = `${$table.data('route')}?&user=${user}&currency=${currency}`;
                    }
                    swalConfirm($button.data('route'), function () {
                        $table.DataTable().ajax.url(route).load();
                    });
                });
            }
        });

        $button.click(function () {
            $button.button('loading');
            let user = $('#user').val();
            let currency = $('#currency').val();
            let route = `${$table.data('route')}?user=${user}&currency=${currency}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    }

    // Get referral totals
    referralTotals() {
        initDateRangePickerEndToday(open = 'right');
        let $table = $('#referral-totals-list-table');
        let $button = $('#search');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.users"
            },
            "order": [[0, 'asc']],
            "columns": [
                {"data": "date"},
                {"data": "currency"},
                {"data": "totals"}
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');
            let user = $('#user').val();
            let currency = $('#currency').val();
            let route = `${$table.data('route')}?user=${user}&currency=${currency}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    }

    // Select2  user
    select2Users(placeholder) {
        $('select2').select2();
        let $user = $('#user');
        $user.select2({
            width: '100%',
            placeholder: placeholder,
            allowClear: true,
            language: 'es',
            id: user.id || user.id,
            text: user.text || user.username,

            ajax: {
                type: "POST",
                url: $user.data('route'),
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        user: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (json, params) {
                    let results = [];

                    $.each(json.data.users, function (gameIndex, value) {
                        results.push({
                            id: value.id,
                            text: value.username
                        });
                    });
                    return {
                        results: results,
                        paginate: {
                            more: false
                        }
                    };
                },
                cache: true
            },

            minimumInputLength: 3,
            templateSelection: function (repo) {
                return repo.user || repo.text;
            },

            escapeMarkup: function (markup) {
                return markup;
            },
            templateResult: function (res) {
                if (res.loading) {
                    return res.text;
                }

                let markup = "<div class='select2-result-repository clearfix'>" +
                    "<div class='select2-result-repository__meta'>" +
                    "<div class='select2-result-repository__title'>" + res.text + "</div>" +
                    "</div></div>";
                return markup;
            }
        });
    }

    // Select2  user refer
    select2UserRefer(placeholder) {
        $('select2').select2();
        let $user = $('#user_refer');
        $user.select2({
            width: '100%',
            placeholder: placeholder,
            allowClear: true,
            language: 'es',
            id: user_refer.id,
            text: user_refer.text,

            ajax: {
                type: "POST",
                url: $user.data('route'),
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        user: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (json, params) {
                    let results = [];

                    $.each(json.data.users, function (gameIndex, value) {
                        results.push({
                            id: value.id,
                            text: value.username
                        });
                    });
                    return {
                        results: results,
                        paginate: {
                            more: false
                        }
                    };
                },
                cache: true
            },

            minimumInputLength: 3,
            templateSelection: function (repo) {
                return repo.user_refer || repo.text;
            },

            escapeMarkup: function (markup) {
                return markup;
            },
            templateResult: function (res) {
                if (res.loading) {
                    return res.text;
                }

                let markup = "<div class='select2-result-repository clearfix'>" +
                    "<div class='select2-result-repository__meta'>" +
                    "<div class='select2-result-repository__title'>" + res.text + "</div>" +
                    "</div></div>";
                return markup;
            }
        });
    }
}

window.Referrals = Referrals;
