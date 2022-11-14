import {clearForm, initDatepickerEndToday, initDateRangePickerEndToday, initFileInput, initSelect2, refreshRandomPassword} from "./commons";
import {
    clipboard,
    swalError,
    swalSuccessWithButton,
    swalConfirm,
    swalInput,
    swalSuccessNoButton
} from "../../commons/js/core";
import moment from "moment";
import { ajaxSetup } from "jquery";

class Audits {
    // search
    search() {
        initDateRangePickerEndToday(open = 'right');
        initSelect2();
        let $table = $('#audits-table');
        let $button = $('#update');
        let api;
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.audits"
            },
            "order": [[1, "asc"]],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "type"},
                {"data": "details"}
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });
        $button.click(function () {
            $button.button('loading');
            let startDate = $('#start_date').val();
            let endDate = $('#end_date').val();
            let type = $('#type').val();
            let users = $('#users').val();
            let route = `${$table.data('route')}/${startDate}/${endDate}?type=${type}&users=${users}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    }

    // Exclude users
    excludeUsers(placeholder) {
        $('select2').select2();
        let $user = $('#users');

        $user.select2({
            width: '100%',
            placeholder: placeholder,
            allowClear: true,
            language: 'es',
            multiple: true,
            id: users.id,
            text: users.text,

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
                return repo.users || repo.text;
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

window.Audits = Audits;
