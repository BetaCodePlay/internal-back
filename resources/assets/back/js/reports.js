import {
    clearForm,
    getCookie,
    initDatepickerStartToday,
    initDateRangePickerEndToday,
    initLitepickerEndToday,
    initSelect2,
    initDateRangePickerWithTime
} from "./commons";
import {swalError, swalSuccessWithButton} from "../../commons/js/core";
import moment from 'moment';
import i18next from 'i18next';
import Backend from 'i18next-http-backend';
import reportsLocale from './i18n/reports.json';

import * as toastr from 'toastr';

class Reports {

    // Bonus transactions report
    bonusTransactions() {
        let picker = initLitepickerEndToday();
        let $table = $('#bonus-transactions-table');
        let $button = $('#update');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.transactions"
            },
            "order": [],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "operator"},
                {"data": "description"},
                {"data": "amount", "className": "text-right", "type": "num-fmt"},
                {"data": "created", "className": "text-right"},
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');
            let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
            let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');
            let route = `${$table.data('route')}/${startDate}/${endDate}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });

        $table.on('xhr.dt', function (event, settings, json, xhr) {
            $('#total').text(json.data.total)
            if (xhr.status === 500) {
                swalError(xhr);
            }
        });
    }

    // Credit report JustPay
    creditReportJustPay() {
        let picker = initLitepickerEndToday();
        let $table = $('#credit-table');
        let $button = $('#update');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.transactions"
            },
            "order": [],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "client"},
                {"data": "reference"},
                {"data": "amount", "className": "text-right", "type": "num-fmt"},
                {"data": "currency_iso"},
                {"data": "created", "className": "text-right"}
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');
            let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
            let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');
            let provider = $('#provider').val();
            let type = $('#type').val();
            let route = `${$table.data('route')}/${provider}/${type}/${startDate}/${endDate}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });

        $table.on('xhr.dt', function (event, settings, json, xhr) {
            $('#total').text(json.data.totals.total);
            if (xhr.status === 500) {
                swalError(xhr);
            }
        });
    }

    // Credit report Zippy
    creditReportZippy() {
        let picker = initLitepickerEndToday();
        let $table = $('#credit-table');
        let $button = $('#update');
        let api;
        let locale = getCookie('language-js');
        locale = (locale === null || locale === '') ? 'en_US' : locale;

        $table.DataTable({
            "language": {
                "url": "/i18n/datatables/" + locale + ".lang"
            },
            "lengthMenu": [[25, 50, 100, 250, 500, 1000], [25, 50, 100, 250, 500, 1000]],
            "processing": false,
            "deferRender": true,
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.transactions"
            },
            "order": [],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "client"},
                {"data": "reference"},
                {"data": "amount", "className": "text-right", "type": "num-fmt"},
                {"data": "currency_iso"},
                {"data": "created", "className": "text-right"}
            ],
            "buttons": {
                "buttons": [
                    {extend: 'excel'},
                    {extend: 'pdf'},
                    {
                        extend: 'print',
                        text: function (dt) {
                            return dt.i18n('buttons.print', 'Print');
                        }
                    },
                    {
                        extend: 'copy',
                        text: function (dt) {
                            return dt.i18n('buttons.copy', 'Copy');
                        }
                    }
                ]
            },
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');
            let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
            let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');
            let provider = $('#provider').val();
            let type = $('#type').val();
            let route = `${$table.data('route')}/${provider}/${type}/${startDate}/${endDate}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });

        $table.on('xhr.dt', function (event, settings, json, xhr) {
            $('#total').text(json.data.totals.total);
            if (xhr.status === 500) {
                swalError(xhr);
            }
        });
    }

    // Users registers by date
    dateRegisters() {
        let picker = initLitepickerEndToday();
        let $table = $('#registered-totals-users-table');
        let $button = $('#update');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.users"
            },
            "order": [],
            "columns": [
                {"data": "date"},
                {"data": "quantity", "className": "text-right", "type": "num-fmt"}
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');
            let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
            let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');
            let route = `${$table.data('route')}/${startDate}/${endDate}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    };

    // Debit report JustPay
    debitReportJustPay() {
        let picker = initLitepickerEndToday();
        let $table = $('#debit-table');
        let $button = $('#update');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.transactions"
            },
            "order": [],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "client"},
                {"data": "reference"},
                {"data": "amount", "className": "text-right", "type": "num-fmt"},
                {"data": "currency_iso"},
                {"data": "created", "className": "text-right"},
                {"data": "updated", "className": "text-right"},
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');
            let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
            let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');
            let provider = $('#provider').val();
            let type = $('#type').val();
            let route = `${$table.data('route')}/${provider}/${type}/${startDate}/${endDate}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });

        $table.on('xhr.dt', function (event, settings, json, xhr) {
            $('#total').text(json.data.totals.total);
            if (xhr.status === 500) {
                swalError(xhr);
            }
        });
    }

    // Debit report Zippy
    debitReportZippy() {
        let picker = initLitepickerEndToday();
        let $table = $('#debit-table');
        let $button = $('#update');
        let api;
        let locale = getCookie('language-js');
        locale = (locale === null || locale === '') ? 'en_US' : locale;

        $table.DataTable({
            "language": {
                "url": "/i18n/datatables/" + locale + ".lang"
            },
            "lengthMenu": [[25, 50, 100, 250, 500, 1000], [25, 50, 100, 250, 500, 1000]],
            "processing": false,
            "deferRender": true,
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.transactions"
            },
            "order": [],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "client"},
                {"data": "reference"},
                {"data": "amount", "className": "text-right", "type": "num-fmt"},
                {"data": "currency_iso"},
                {"data": "created", "className": "text-right"},
                {"data": "updated", "className": "text-right"},
            ],
            "buttons": {
                "buttons": [
                    {extend: 'excel'},
                    {extend: 'pdf'},
                    {
                        extend: 'print',
                        text: function (dt) {
                            return dt.i18n('buttons.print', 'Print');
                        }
                    },
                    {
                        extend: 'copy',
                        text: function (dt) {
                            return dt.i18n('buttons.copy', 'Copy');
                        }
                    }
                ]
            },
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');
            let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
            let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');
            let provider = $('#provider').val();
            let type = $('#type').val();
            let route = `${$table.data('route')}/${provider}/${type}/${startDate}/${endDate}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });

        $table.on('xhr.dt', function (event, settings, json, xhr) {
            $('#total').text(json.data.totals.total);
            if (xhr.status === 500) {
                swalError(xhr);
            }
        });
    }

    // Deposits report
    depositsWithdrawals() {
        let picker = initLitepickerEndToday();
        let $table = $('#deposits-withdrawals-table');
        let $button = $('#update');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.transactions"
            },
            "order": [[3, 'desc']],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "wallet"},
                {"data": "amount", "className": "text-right", "type": "num-fmt"},
                {"data": "created", "className": "text-right", "type": "date"},
                {"data": "updated", "className": "text-right", "type": "date"},
                {"data": "operator"},
                {"data": "description"},
                {"data": "currency"},
                {"data": "info"},
                {"data": "provider"},
                {"data": "status", "className": "text-right"},
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');
            let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
            let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');
            let status = $('#status').val();
            let transactionType = $('#transaction_type').val();
            let route = `${$table.data('route')}/${startDate}/${endDate}/${transactionType}?status=${status}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });

        $table.on('xhr.dt', function (event, settings, json, xhr) {
            $('#total').text(json.data.total)
            if (xhr.status === 500) {
                swalError(xhr);
            }
        });
    }

    // Dotpanel registered users report
    dotpanelRegisteredUsers() {
        initDateRangePickerEndToday(open = 'right');
        let $table = $('#registered-users-table');
        let $button = $('#update');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.users"
            },
            "order": [[1, 'asc']],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "email"},
                {"data": "date", "className": "text-right"},
                {"data": "first_name"},
                {"data": "last_name"},
                {"data": "country"},
                {"data": "phone"},
                {"data": "deposits", "type": "num-fmt"},
                {"data": "status"},
            ],

            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');
            let country = $('#country').val();
            let deposits = $('#deposits').val();
            let endDate = $('#end_date').val();
            let startDate = $('#start_date').val();
            let status = $('#status').val();
            let tester = $('#tester').val();
            let web_register = $('#web_register').val();
            let route = `${$table.data('route')}/${startDate}/${endDate}?country=${country}&deposits=${deposits}&status=${status}&tester=${tester}&web_register=${web_register}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    };

    // Financial totals reports
    financialTotals() {
        let picker = initLitepickerEndToday();
        let $table = $('#totals-table');
        let $button = $('#update');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.financial"
            },
            "order": [[0, "desc"]],
            "columns": [
                {"data": "date"},
                {"data": "credit", "className": "text-right", "type": "num-fmt"},
                {"data": "debit", "className": "text-right", "type": "num-fmt"},
                {"data": "profit", "className": "text-right", "type": "num-fmt"},
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');
            let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
            let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');
            let payment_method = $('#payment_method').val();
            localStorage.setItem(`payment-method`, payment_method);
            let route = `${$table.data('route')}/${startDate}/${endDate}?payment_method=${payment_method}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });

        $table.on('xhr.dt', function (event, setting, json, xhr) {
            $('#credit').text(json.data.totals.credit);
            $('#debit').text(json.data.totals.debit);
            $('#profit').text(json.data.totals.profit);
        });
    };

    //Select paymen method
    selectPayment() {
        let paymentMethod = $('#payment_method');
        if(localStorage.getItem(`payment-method`) !== undefined){
            paymentMethod.val(localStorage.getItem(`payment-method`)).trigger('change');
        }
    }

    // Games totals report
    gamesTotals() {
        let picker = initLitepickerEndToday();
        let $table = $('#games-table');
        let $button = $('#update');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.games"
            },
            "order": [[3, 'desc']],
            "columns": [
                {"data": "name"},
                {"data": "platform"},
                {"data": "bets", "className": "text-right", "type": "num-fmt"},
                {"data": "average", "className": "text-right", "type": "num-fmt"},
                {"data": "played", "className": "text-right", "type": "num-fmt"},
                {"data": "won", "className": "text-right", "type": "num-fmt"},
                {"data": "profit", "className": "text-right", "type": "num-fmt"},
                {"data": "rtp", "className": "text-right", "type": "num-fmt"},
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');
            let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
            let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');
            let provider = $('#provider').val();
            let route = `${$table.data('route')}/${startDate}/${endDate}/${provider}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });

        $table.on('xhr.dt', function (event, setting, json, xhr) {
            $('#played').text(json.data.totals.played);
            $('#won').text(json.data.totals.won);
            $('#profit').text(json.data.totals.profit);
            $('#rtp').text(json.data.totals.rtp)
        });
    };

    // Games played by userreport
    gamesPlayedByUser(support = false) {
        initSelect2();
        let picker;

        if (support) {
            initDateRangePickerEndToday();
        } else {
            picker = initLitepickerEndToday();
        }

        let $table = $('#users-bets-games-table');
        let $button = $('#update');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.users"
            },
            "order": [[3, 'desc']],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "game"},
                {"data": "played", "className": "text-right", "type": "num-fmt"},
                {"data": "won", "className": "text-right", "type": "num-fmt"},
                {"data": "profit", "className": "text-right", "type": "num-fmt"}
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');
            let startDate;
            let endDate;

            if (support) {
                startDate = $('#start_date').val();
                endDate = $('#end_date').val();
            } else {
                startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
                endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');
            }
            let provider = $('#provider').val();
            let route = `${$table.data('route')}/${startDate}/${endDate}/${provider}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });

        $table.on('xhr.dt', function (event, settings, json, xhr) {
            if (xhr.status === 500) {
                swalError(xhr);
            }
        });
    }

    // Hour totals
    hourTotals() {
        initSelect2();
        let picker = initLitepickerEndToday();
        let $table = $('#hour-table');
        let $button = $('#update');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data"
            },
            "order": [],
            "columns": [
                {"data": "whitelabel_id", "className": "text-center"},
                {"data": "provider_id", "className": "text-center"},
                {"data": "played", "className": "text-right", "type": "num-fmt"},
                {"data": "won", "className": "text-right", "type": "num-fmt"},
                {"data": "profit", "className": "text-right", "type": "num-fmt"},
                {"data": "rtp", "className": "text-right", "type": "num-fmt"},
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');
            let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
            let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');
            let currency = $('#currency').val();
            let whitelabel = $('#whitelabel').val();
            let provider = $('#provider').val();
            let route = `${$table.data('route')}/${startDate}/${endDate}/${currency}&'?provider'=${provider}&'?whitelabel'=${whitelabel}`;

            api.ajax.url(route).load();
            $table.on('draw.dt', function (json) {
                $('#hour-table').css({width: "100%"});
                $('#played').text(json.totals.played);
                $('#won').text(json.totals.won);
                $('#profit').text(json.totals.profit);
                $('#rtp').text(json.totals.rtp);
                $table.rows.add(json.data).draw();
                $button.button('reset');
            });
        });

        $table.on('xhr.dt', function (event, setting, json, xhr) {
            $('#played').text(json.totals.played);
            $('#won').text(json.totals.won);
            $('#profit').text(json.totals.profit);
            $('#rtp').text(json.totals.rtp);
        });
    };

    // IQ Soft tickets report
    iqSoftTickets() {
        let picker = initLitepickerEndToday();
        let $table = $('#iq-soft-tickets-table');
        let $button = $('#update');
        let api;
        let locale = getCookie('language-js');
        locale = (locale === null || locale === '') ? 'en_US' : locale;

        i18next.use(Backend)
            .init({
                lng: locale,
                resources: reportsLocale
            });

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.tickets"
            },
            "columns": [
                {
                    "className": 'details-control',
                    "orderable": false,
                    "data": null,
                    "defaultContent": ''
                },
                {"data": "date"},
                {"data": "provider_transaction"},
                {"data": "amount", "className": "text-right", "type": "num-fmt"},
                {"data": "status", "className": "text-right"},
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });

        $(document).on('click', '#iq-soft-tickets-table tbody td.details-control', function () {
            let tr = $(this).closest('tr');
            let row = $table.DataTable().row(tr);

            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            } else {
                row.child(function () {
                    let $div = $('<div/>').addClass('loading').text(i18next.t('loading'));

                    $.ajax({
                        url: $table.data('ticket-info-route') + '/' + row.data().provider_transaction,
                        type: 'get',
                        dataType: 'json'

                    }).done(function (json) {
                        $div.html(json.data.ticket).removeClass('loading');

                    }).fail(function (json) {
                        swalError(json);
                    });
                    return $div;
                }).show();
                tr.addClass('shown');
            }
        });

        $button.click(function () {
            $button.button('loading');
            let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
            let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');
            let route = `${$table.data('route')}/${startDate}/${endDate}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    };

    // IQ Soft totals report
    iqSoftTotals() {
        initSelect2();
        let picker = initLitepickerEndToday();
        let $table = $('#iq-soft-totals-table');
        let api;
        let $button = $('#search');
        let whitelabel = $('#whitelabel').val();
        let currency = $('#currency').val();
        let locale = getCookie('language-js');
        locale = (locale === null || locale === '') ? 'en_US' : locale;

        i18next.use(Backend)
            .init({
                lng: locale,
                resources: reportsLocale
            });

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.totals"
            },
            "order": [[3, 'desc']],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "date"},
                {"data": "provider_transaction"},
                {"data": "amounts"},
                {"data": "status", "className": "text-right"},
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
            let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
            let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');
            whitelabel = $('#whitelabel').val();
            currency = $('#currency').val();
            let route = `${$table.data('route')}/${startDate}/${endDate}/${whitelabel}/${currency}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });

    };

    // Manual transactions report
    manualTransactions() {
        let picker = initLitepickerEndToday();
        let $table = $('#manual-transactions-table');
        let $button = $('#update');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.transactions"
            },
            "order": [],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "amount", "className": "text-right", "type": "num-fmt"},
                {"data": "created", "className": "text-right"},
                {"data": "updated", "className": "text-right"},
                {"data": "operator"},
                {"data": "description"},
                {"data": "status", "className": "text-right"},
                {"data": "provider"},
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');
            let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
            let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');
            let transactionType = $('#transaction_type').val();
            let route = `${$table.data('route')}/${startDate}/${endDate}/${transactionType}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });

        $table.on('xhr.dt', function (event, settings, json, xhr) {
            $('#total').text(json.data.total)
            if (xhr.status === 500) {
                swalError(xhr);
            }
        });
    }

    // Manual adjustments
    manualAdjustments() {
        initDateRangePickerEndToday(open = 'right');
        let $table = $('#manual-adjustments-table');
        let $button = $('#update');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.transactions"
            },
            "order": [],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "operator"},
                {"data": "whitelabel_description", "className": "text-right"},
                {"data": "transaction_type"},
                {"data": "amount", "className": "text-right", "type": "num-fmt"},
                {"data": "currency"},
                {"data": "description"},
                {"data": "provider"},
                {"data": "created", "className": "text-right"}
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
            let transactionType = $('#transaction_type').val();
            let whitelabel = $('#whitelabel').val();
            let currency = $('#currency').val();
            let route = `${$table.data('route')}/?start_date=${startDate}&end_date=${endDate}&transactionType=${transactionType}&whitelabel=${whitelabel}&currency=${currency}`;

            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });

        $table.on('xhr.dt', function (event, settings, json, xhr) {
            if (xhr.status === 500) {
                swalError(xhr);
            }
        });
    }

    // Manual adjustments
    manualAdjustmentsWhitelabel() {
        initDateRangePickerEndToday(open = 'right');
        let $table = $('#manual-adjustments-table');
        let $button = $('#update');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.transactions"
            },
            "order": [],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "operator"},
                {"data": "transaction_type"},
                {"data": "amount", "className": "text-right", "type": "num-fmt"},
                {"data": "currency"},
                {"data": "description"},
                {"data": "provider"},
                {"data": "created", "className": "text-right"}
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
            let transactionType = $('#transaction_type').val();
            let currency = $('#currency').val();
            let route = `${$table.data('route')}/${startDate}/${endDate}?transactionType=${transactionType}&currency=${currency}`;

            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });

        $table.on('xhr.dt', function (event, settings, json, xhr) {
            if (xhr.status === 500) {
                swalError(xhr);
            }
        });
    }

    // Most played games report
    mostPlayedGames() {
        let picker = initLitepickerEndToday();
        let $table = $('#most-played-games-table');
        let $button = $('#update');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.games"
            },
            "order": [[2, 'asc']],
            "columns": [
                {"data": "name"},
                {"data": "platform"},
                {"data": "bets", "className": "text-right", "type": "num-fmt"},
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');
            let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
            let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');
            let provider = $('#provider').val();
            let route = `${$table.data('route')}/${startDate}/${endDate}/${provider}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    };

    // Most played by providers report
    mostPlayedByProviders() {
        initDateRangePickerEndToday(open = 'right');
        let $table = $('#most-played-by-providers-table');
        let $button = $('#update');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.games"
            },
            "order": [[2, 'asc']],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "email"},
                {"data": "provider"},
                {"data": "currency_iso"},
                {"data": "max_played", "className": "text-right", "type": "num-fmt"},
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
            let currency = $('#currency').val();
            let route = `${$table.data('route')}/${startDate}/${endDate}/${currency}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    };

    // Monthly daily
    monthlySales() {
        $("#month").select2({maximumSelectionLength: 2});
        let $table = $('#monthly-sales-table');
        let $button = $('#update');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.sales"
            },
            "order": [[0, 'asc']],
            "lengthMenu": [[12], [12]],
            "columns": [
                {"data": "date"},
                {"data": "new_registers", "className": "text-right", "type": "num-fmt"},
                {"data": "unique_depositors", "className": "text-right", "type": "num-fmt"},
                {"data": "active_users", "className": "text-right", "type": "num-fmt"},
                {"data": "ftd", "className": "text-right", "type": "num-fmt"},
                {"data": "bonus", "className": "text-right", "type": "num-fmt"},
                {"data": "credit_approved", "className": "text-right", "type": "num-fmt"},
                {"data": "credit_rejected", "className": "text-right", "type": "num-fmt"},
                {"data": "debit_approved", "className": "text-right", "type": "num-fmt"},
                {"data": "debit_rejected", "className": "text-right", "type": "num-fmt"},
                {"data": "credit_manual", "className": "text-right", "type": "num-fmt"},
                {"data": "debit_manual", "className": "text-right", "type": "num-fmt"},
                {"data": "played", "className": "text-right", "type": "num-fmt"},
                {"data": "profit", "className": "text-right", "type": "num-fmt"},
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            },
            "footerCallback": function (tfoot, data, start, end, display) {
                api = this.api();
                let response = api.ajax.json();
                if (response) {
                    response = response.data;
                    $('tr:eq(0) th:eq(1)', api.table().footer()).html(
                        response.percentage_new_registers
                    ).addClass('text-right');
                    $('tr:eq(0) th:eq(2)', api.table().footer()).html(
                        response.percentage_unique_depositors
                    ).addClass('text-right');
                    $('tr:eq(0) th:eq(3)', api.table().footer()).html(
                        response.percentage_ftd
                    ).addClass('text-right');
                    $('tr:eq(0) th:eq(4)', api.table().footer()).html(
                        response.percentage_bonus
                    ).addClass('text-right');
                    $('tr:eq(0) th:eq(5)', api.table().footer()).html(
                        response.percentage_credit_approved
                    ).addClass('text-right');
                    $('tr:eq(0) th:eq(6)', api.table().footer()).html(
                        response.percentage_credit_rejected
                    ).addClass('text-right');
                    $('tr:eq(0) th:eq(7)', api.table().footer()).html(
                        response.percentage_debit_approved
                    ).addClass('text-right');
                    $('tr:eq(0) th:eq(8)', api.table().footer()).html(
                        response.percentage_debit_rejected
                    ).addClass('text-right');
                    $('tr:eq(0) th:eq(9)', api.table().footer()).html(
                        response.percentage_credit_manual
                    ).addClass('text-right');
                    $('tr:eq(0) th:eq(10)', api.table().footer()).html(
                        response.percentage_debit_manual
                    ).addClass('text-right');
                    $('tr:eq(0) th:eq(11)', api.table().footer()).html(
                        response.percentage_played
                    ).addClass('text-right');
                    $('tr:eq(0) th:eq(12)', api.table().footer()).html(
                        response.percentage_profit
                    ).addClass('text-right');
                    $('tr:eq(1) th:eq(1)', api.table().footer()).html(
                        response.total_new_registers
                    ).addClass('text-right');
                    $('tr:eq(1) th:eq(2)', api.table().footer()).html(
                        response.total_unique_depositors
                    ).addClass('text-right');
                    $('tr:eq(1) th:eq(3)', api.table().footer()).html(
                        response.total_ftd
                    ).addClass('text-right');
                    $('tr:eq(1) th:eq(4)', api.table().footer()).html(
                        response.total_bonus
                    ).addClass('text-right');
                    $('tr:eq(1) th:eq(5)', api.table().footer()).html(
                        response.total_credit_approved
                    ).addClass('text-right');
                    $('tr:eq(1) th:eq(6)', api.table().footer()).html(
                        response.total_credit_rejected
                    ).addClass('text-right');
                    $('tr:eq(1) th:eq(7)', api.table().footer()).html(
                        response.total_debit_approved
                    ).addClass('text-right');
                    $('tr:eq(1) th:eq(8)', api.table().footer()).html(
                        response.total_debit_rejected
                    ).addClass('text-right');
                    $('tr:eq(1) th:eq(9)', api.table().footer()).html(
                        response.total_credit_manual
                    ).addClass('text-right');
                    $('tr:eq(1) th:eq(10)', api.table().footer()).html(
                        response.total_debit_manual
                    ).addClass('text-right');
                    $('tr:eq(1) th:eq(11)', api.table().footer()).html(
                        response.total_played
                    ).addClass('text-right');
                    $('tr:eq(1) th:eq(12)', api.table().footer()).html(
                        response.total_profit
                    ).addClass('text-right');
                }
            }
        });

        $button.click(function () {
            $button.button('loading');
            let convert = $('#convert').val();
            let currency = $('#currency').val();
            let year = $('#years').val();
            let month = $('#month').val();
            let route = `${$table.data('route')}?year=${year}&month=${month}&convert=${convert}&currency=${currency}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });

        $table.on('xhr.dt', function (event, settings, json, xhr) {
            if (xhr.status === 500 || xhr.status === 422) {
                swalError(xhr);
                $button.button('reset');
            }
        });
    }

    // payment total report
    paymentTotal() {
        initDateRangePickerEndToday(open = 'right');
        let $table = $('#payment-totals-table');
        let $button = $('#update');
        let api;
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.totals"
            },
            "order": [[0, 'asc']],
            "columns": [
                {"data": "payment_method"},
                {"data": "credit_pending", "className": "text-right"},
                {"data": "credit_approved", "className": "text-right"},
                {"data": "debit_pending", "className": "text-right"},
                {"data": "debit_approved", "className": "text-right"},
                {"data": "profit", "className": "text-right"},

            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));

                let currency = $('#currency').val();
                if (currency === '') {
                    $('.totals').addClass('d-none');
                } else {
                    $('.totals').removeClass('d-none');
                }
            }
        });

        $button.click(function () {
            $button.button('loading');
            let startDate = $('#start_date').val();
            let endDate = $('#end_date').val();
            let currency = $('#currency').val();
            let paymentMethod = $('#payment_method').val();
            let route = `${$table.data('route')}/${startDate}/${endDate}?currency=${currency}&payment_method=${paymentMethod}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });

            if (currency === '') {
                $('.totals').addClass('d-none');
            } else {
                $('.totals').removeClass('d-none');
            }
        });

        $table.on('xhr.dt', function (event, setting, json, xhr) {
            $('#credit-pending').text(json.data.general_totals.credit_pending);
            $('#credit-approved').text(json.data.general_totals.credit_approved);
            $('#debit-pending').text(json.data.general_totals.debit_pending);
            $('#debit-approved').text(json.data.general_totals.debit_approved);
            $('#profit').text(json.data.general_totals.profit);
        });
    }

    // Products totals report
    productsTotals() {
        initDateRangePickerEndToday(open = 'right');
        let $table = $('#products-totals-table');
        let $button = $('#update');
        let api;
        $('#provider').change(function () {
            if ($(this).val() !== '') {
                $('.type').addClass('d-none');
                $('#type').val('');
            } else {
                $('.type').removeClass('d-none');
            }
        });

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.totals"
            },
            "order": [[7, 'desc']],
            "columns": [
                {"data": "provider"},
                {"data": "provider_type"},
                {"data": "users", "className": "text-right", "type": "num-fmt"},
                {"data": "latest_users", "className": "text-right", "type": "num-fmt"},
                {"data": "bets", "className": "text-right", "type": "num-fmt"},
                {"data": "latest_bets", "className": "text-right", "type": "num-fmt"},
                {"data": "played", "className": "text-right", "type": "num-fmt"},
                {"data": "won", "className": "text-right", "type": "num-fmt"},
                {"data": "profit", "className": "text-right", "type": "num-fmt"},
                {"data": "rtp", "className": "text-right", "type": "num-fmt"},
                {"data": "hold", "className": "text-right", "type": "num-fmt"},
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
            let currency = $('#currency').val();
            let provider = $('#provider').val();
            let convert = $('#convert').val();
            let type = $('#type').val();
            let route = `${$table.data('route')}/${startDate}/${endDate}?currency=${currency}&convert=${convert}&provider=${provider}&type=${type}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });

            if (currency === '') {
                $('.totals').addClass('d-none');
            } else {
                $('.totals').removeClass('d-none');
            }
        });

        $table.on('xhr.dt', function (event, setting, json, xhr) {
            if (xhr.status === 500 || xhr.status === 422) {
                swalError(xhr);
                $button.button('reset');
            }

            $('#played').text(json.data.totals_general.played);
            $('#won').text(json.data.totals_general.won);
            $('#profit').text(json.data.totals_general.profit);
            $('#rtp').text(json.data.totals_general.rtp);
            $('#hold').text(json.data.totals_general.hold);
        });
    };

    // Products totals overview
    productsTotalsOverview() {
        initDateRangePickerEndToday(open = 'right');
        let $table = $('#products-totals-overview-table');
        let $button = $('#update');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.totals"
            },
            "order": [[0, "asc"]],
            "columns": [
                {"data": "provider"},
                {"data": "provider_type"},
                {"data": "played", "className": "text-right", "type": "num-fmt"},
                {"data": "won", "className": "text-right", "type": "num-fmt"},
                {"data": "profit", "className": "text-right", "type": "num-fmt"},
                {"data": "rtp", "className": "text-right", "type": "num-fmt"},
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
            let currency = $('#currency').val();
            let provider = $('#provider').val();
            let convert = $('#convert').val();
            let route = `${$table.data('route')}/${startDate}/${endDate}?currency=${currency}&convert=${convert}&provider=${provider}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });

        $table.on('xhr.dt', function (event, settings, json, xhr) {
            if (xhr.status === 500 || xhr.status === 422) {
                swalError(xhr);
                $button.button('reset');
            }
        });
    };

    // Profit by user
    profitByUser() {
        let $table = $('#profit-by-user-table');
        let $button = $('#search');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.totals"
            },
            "order": [],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "deposits", "className": "text-right", "type": "num-fmt"},
                {"data": "withdrawals", "className": "text-right", "type": "num-fmt"},
                {"data": "percentage", "className": "text-right", "type": "num-fmt"},
                // {"data": "amount", "className": "text-right", "type": "num-fmt"},
                {"data": "login", "className": "text-right"}
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
            let level = $('#level').val();
            let route = `${$table.data('route')}?currency=${currency}&level=${level}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });

        $table.on('xhr.dt', function (event, settings, json, xhr) {
            if (xhr.status === 500 || xhr.status === 422) {
                swalError(xhr);
                $button.button('reset');
            }
        });
    }

    // Referred users report
    referredUsers() {
        let picker = initLitepickerEndToday();
        let $table = $('#referred-users-table');
        let $button = $('#update');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.users"
            },
            "order": [[2, 'desc']],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "referral"},
                {"data": "created_at", "className": "text-right"}
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');
            let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
            let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');
            let route = `${$table.data('route')}/${startDate}/${endDate}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    };

    // Sales daily
    dailySales() {
        let $table = $('#sales-daily-table');
        let $button = $('#update');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.sales"
            },
            "order": [[0, 'asc']],
            "lengthMenu": [[31], [31]],
            "columns": [
                {"data": "date"},
                {"data": "new_registers", "className": "text-right", "type": "num-fmt"},
                {"data": "unique_depositors", "className": "text-right", "type": "num-fmt"},
                {"data": "active_users", "className": "text-right", "type": "num-fmt"},
                {"data": "ftd", "className": "text-right", "type": "num-fmt"},
                {"data": "bonus", "className": "text-right", "type": "num-fmt"},
                {"data": "credit_approved", "className": "text-right", "type": "num-fmt"},
                {"data": "credit_rejected", "className": "text-right", "type": "num-fmt"},
                {"data": "debit_approved", "className": "text-right", "type": "num-fmt"},
                {"data": "debit_rejected", "className": "text-right", "type": "num-fmt"},
                {"data": "credit_manual", "className": "text-right", "type": "num-fmt"},
                {"data": "debit_manual", "className": "text-right", "type": "num-fmt"},
                {"data": "played", "className": "text-right", "type": "num-fmt"},
                {"data": "profit", "className": "text-right", "type": "num-fmt"},
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));

            },
            "footerCallback": function (tfoot, data, start, end, display) {
                api = this.api();
                let response = api.ajax.json();
                if (response) {
                    response = response.data;
                    $('tr:eq(0) th:eq(1)', api.table().footer()).html(
                        response.percentage_new_registers
                    ).addClass('text-right');
                    $('tr:eq(0) th:eq(2)', api.table().footer()).html(
                        response.percentage_unique_depositors
                    ).addClass('text-right');
                    $('tr:eq(0) th:eq(3)', api.table().footer()).html(
                        response.percentage_ftd
                    ).addClass('text-right');
                    $('tr:eq(0) th:eq(4)', api.table().footer()).html(
                        response.percentage_bonus
                    ).addClass('text-right');
                    $('tr:eq(0) th:eq(5)', api.table().footer()).html(
                        response.percentage_credit_approved
                    ).addClass('text-right');
                    $('tr:eq(0) th:eq(6)', api.table().footer()).html(
                        response.percentage_credit_rejected
                    ).addClass('text-right');
                    $('tr:eq(0) th:eq(7)', api.table().footer()).html(
                        response.percentage_debit_approved
                    ).addClass('text-right');
                    $('tr:eq(0) th:eq(8)', api.table().footer()).html(
                        response.percentage_debit_rejected
                    ).addClass('text-right');
                    $('tr:eq(0) th:eq(9)', api.table().footer()).html(
                        response.percentage_credit_manual
                    ).addClass('text-right');
                    $('tr:eq(0) th:eq(10)', api.table().footer()).html(
                        response.percentage_debit_manual
                    ).addClass('text-right');
                    $('tr:eq(0) th:eq(11)', api.table().footer()).html(
                        response.percentage_played
                    ).addClass('text-right');
                    $('tr:eq(0) th:eq(12)', api.table().footer()).html(
                        response.percentage_profit
                    ).addClass('text-right');
                    $('tr:eq(1) th:eq(1)', api.table().footer()).html(
                        response.total_new_registers
                    ).addClass('text-right');
                    $('tr:eq(1) th:eq(2)', api.table().footer()).html(
                        response.total_unique_depositors
                    ).addClass('text-right');
                    $('tr:eq(1) th:eq(3)', api.table().footer()).html(
                        response.total_ftd
                    ).addClass('text-right');
                    $('tr:eq(1) th:eq(4)', api.table().footer()).html(
                        response.total_bonus
                    ).addClass('text-right');
                    $('tr:eq(1) th:eq(5)', api.table().footer()).html(
                        response.total_credit_approved
                    ).addClass('text-right');
                    $('tr:eq(1) th:eq(6)', api.table().footer()).html(
                        response.total_credit_rejected
                    ).addClass('text-right');
                    $('tr:eq(1) th:eq(7)', api.table().footer()).html(
                        response.total_debit_approved
                    ).addClass('text-right');
                    $('tr:eq(1) th:eq(8)', api.table().footer()).html(
                        response.total_debit_rejected
                    ).addClass('text-right');
                    $('tr:eq(1) th:eq(9)', api.table().footer()).html(
                        response.total_credit_manual
                    ).addClass('text-right');
                    $('tr:eq(1) th:eq(10)', api.table().footer()).html(
                        response.total_debit_manual
                    ).addClass('text-right');
                    $('tr:eq(1) th:eq(11)', api.table().footer()).html(
                        response.total_played
                    ).addClass('text-right');
                    $('tr:eq(1) th:eq(12)', api.table().footer()).html(
                        response.total_profit
                    ).addClass('text-right');
                }
            }
        });

        $button.click(function () {
            $button.button('loading');
            let convert = $('#convert').val();
            let currency = $('#currency').val();
            let year = $('#years').val();
            let month = $('#month').val();
            let route = `${$table.data('route')}?year=${year}&month=${month}&convert=${convert}&currency=${currency}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });

        $table.on('xhr.dt', function (event, settings, json, xhr) {
            if (xhr.status === 500 || xhr.status === 422) {
                swalError(xhr);
                $button.button('reset');
            }
        });
    }

    // Advanced search
    segmentations() {
        initSelect2();
        initDateRangePickerEndToday(open = 'right');
        let api;
        let $table = $('#segmentation-table');
        let $button = $('#search');
        let $form = $('#segmentation-form');

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
                {"data": "currency"},
                {"data": "country"},
                {"data": "language"},
                {"data": "deposit", "className": "text-right"},
                {"data": "login", "className": "text-right"},
                {"data": "balance", "className": "text-right", "type": "num-fmt"},
                {"data": "bonus", "className": "text-right", "type": "num-fmt"},
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));

                $button.click(function () {
                    $button.button('loading');
                    let route = $form.attr('action') + '?' + $form.serialize();
                    api.ajax.url(route).load();
                    $table.on('draw.dt', function () {
                        $button.button('reset');
                    });
                });

                $form.keypress(function (event) {
                    if (event.keyCode === 13) {
                        $button.click();
                    }
                });
            }
        });

        $table.on('xhr.dt', function (event, settings, json, xhr) {
            if (xhr.status === 500 || xhr.status === 422) {
                swalError(xhr);
                $button.button('reset');
            }
        });
    }

    // Total logins
    totalLogins() {
        initDateRangePickerEndToday(open = 'right');
        let $table = $('#total-logins-table');
        let $button = $('#update');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.logins"
            },
            "order": [],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "logins", "className": "text-right"}
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
            let route = `${$table.data('route')}/${startDate}/${endDate}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    };

    // User active report
    userActive() {
        let picker = initLitepickerEndToday();
        let $table = $('#user-active-table');
        let $button = $('#update');
        let api;
        $table.DataTable({
            "processing": false,
            "deferRender": true,
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.users"
            },
            "order": [],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "email"},
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
            let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
            let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');
            let route = `${$table.data('route')}/${startDate}/${endDate}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });

        $table.on('xhr.dt', function (event, settings, json, xhr) {
            if (xhr.status === 500) {
                swalError(xhr);
            }
        });
    };

    // Balances users report
    usersBalances() {
        let $table = $('#users-balances-table');
        let $button = $('#update');
        let api;

        /*$('#users-balances-table thead th').each(function () {
            var title = $('#users-balances-table thead th').eq($(this).index()).text();
            $(this).html('<input type="text" placeholder="Search ' + title + '" />');
        });*/

        $table.DataTable({
            /*"serverSide": true,*/
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.wallets"
            },
            "order": [[2, 'desc']],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "balance", "className": "text-right", "type": "num-fmt"},
                {"data": "bonus_balance", "className": "text-right", "type": "num-fmt"},
                {"data": "balance_locked", "className": "text-right", "type": "num-fmt"},
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));

                /*api.columns().every(function () {
                    var that = this;
                    $('input', this.footer()).on('keyup change', function () {
                        that
                            .search(this.value)
                            .draw();
                    });
                });*/
            }
        });

        $button.click(function () {
            $button.button('loading');
            let currency = $('#currency').val();
            //let level = $('#level').val();
            let route = `${$table.data('route')}/${currency}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });

        $table.on('xhr.dt', function (event, setting, json) {
            $('#total_balances').text(json.data.total_balances);
            $('#total_locked_balances').text(json.data.total_locked_balances);
            //$('#total_bonus_balances').text(json.data.total_bonus_balances);
            //$('#total_points').text(json.data.total_points);
        });
    };

    // users birthdays report
    usersBirthdays() {
        initDatepickerStartToday();
        initSelect2();
        let $table = $('#users-birthdays-table');
        let $button = $('#search');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data"
            },
            "order": [],
            "columns": [
                {"data": "user"},
                {"data": "user_name"},
                {"data": "email"},
                {"data": "phone"},
                {"data": "birth_date"}
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');
            let date = $('#start_date').val();
            let route = `${$table.data('route')}/${date}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    }

    // Users conversion report
    usersConversion() {
        let picker = initLitepickerEndToday();
        let $table = $('#users-conversion-table');
        let $button = $('#update');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.users"
            },
            "order": [[5, 'desc']],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "level"},
                {"data": "email"},
                {"data": "dni"},
                {"data": "created"},
                {"data": "phone"},
                {"data": "last_login_user"},
                {"data": "profile", "className": "text-right"},
                {"data": "deposits", "className": "text-right", "type": "num-fmt"},
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');
            let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
            let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');
            let route = `${$table.data('route')}/${startDate}/${endDate}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });

        $table.on('xhr.dt', function (event, setting, json, xhr) {
            $('#users').text(json.data.totals.users);
            $('#completed-profiles').text(json.data.totals.completed_profiles);
            $('#percentage-profiles').text(json.data.totals.percentage_profiles);
            $('#deposits').text(json.data.totals.deposits);
            $('#percentage-deposits').text(json.data.totals.percentage_deposits);
        });
    };



    // Update percentage
    static updatePercentage(api, route) {
        let $button = $('#update-button');
        let $form = $('#update-percentage-form');
        let $modal = $('#update-percentage');

        $modal.on('show.bs.modal', function(event) {
            let $target = $(event.relatedTarget);
            $('#credential').val($target.data('credential'));
            $('.currency').val($target.data('currency'));
            $('#percentage').val($target.data('percentage'));
            $('.provider').val($target.data('provider'));
        })

        $button.click(function () {
            $button.button('loading');

            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                data: $form.serialize()

            }).done(function (json) {
                api.ajax.url(route).load();
                swalSuccessWithButton(json);
                $('#update-percentage').modal('hide');
            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }

    // Users totals report
    usersTotals(support = false) {
        let picker;

        if (support) {
            initDateRangePickerEndToday();
        } else {
            picker = initLitepickerEndToday();
        }

        let $table = $('#users-table');
        let $button = $('#update');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.users"
            },
            "order": [[4, 'desc']],
            "columns": [
                {"data": "user"},
                {"data": "wallet"},
                {"data": "username"},
                {"data": "bets", "className": "text-right", "type": "num-fmt"},
                {"data": "average", "className": "text-right", "type": "num-fmt"},
                {"data": "played", "className": "text-right", "type": "num-fmt"},
                {"data": "won", "className": "text-right", "type": "num-fmt"},
                {"data": "profit", "className": "text-right", "type": "num-fmt"},
                {"data": "rtp", "className": "text-right", "type": "num-fmt"}
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');
            let startDate;
            let endDate;

            if (support) {
                startDate = $('#start_date').val();
                endDate = $('#end_date').val();
            } else {
                startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
                endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');
            }
            let provider = $('#provider').val();
            let route = `${$table.data('route')}/${startDate}/${endDate}/${provider}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });

        $table.on('xhr.dt', function (event, setting, json, xhr) {
            $('#played').text(json.data.totals.played);
            $('#won').text(json.data.totals.won);
            $('#profit').text(json.data.totals.profit);
            $('#rtp').text(json.data.totals.rtp);
        });
    };

    // Web registered users report
    webRegisteredUsers() {
        initDateRangePickerEndToday(open = 'right');
        let $table = $('#registered-users-table');
        let $button = $('#update');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.users"
            },
            "order": [[1, 'asc']],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "email"},
                {"data": "date", "className": "text-right"},
                {"data": "full_name"},
                {"data": "dni"},
                {"data": "country"},
                {"data": "level"},
                {"data": "registration_currency", "className": "text-right"},
                {"data": "phone"},
                {"data": "meet_us"},
                {"data": "deposits", "className": "text-right", "type": "num-fmt"},
                {"data": "referral_code"},
                {"data": "promo_code"},
                {"data": "status"},
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');
            let country = $('#country').val();
            let deposits = $('#deposits').val();
            let endDate = $('#end_date').val();
            let options = $('#options').val();
            let startDate = $('#start_date').val();
            let status = $('#status').val();
            let web_register = $('#web_register').val();
            let level = $('#level').val();
            let route = `${$table.data('route')}/${startDate}/${endDate}?country=${country}&deposits=${deposits}&options=${options}&status=${status}&web_register=${web_register}&level=${level}`;
            console.log(route)
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    };

    // Whitelabels active providers
    whitelabelsActiveProviders() {
        initSelect2();
        let $table = $('#whitelabels-active-providers-table');
        let $button = $('#search');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.products"
            },
            "order": [[0, "asc"]],
            "columns": [
                {"data": "whitelabel"},
                {"data": "provider"},
                {"data": "currency_iso"},
                {"data": "percentage", "className": "text-right", "type": "num-fmt"},
                {"data": "actions", "className": "text-right"},
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
                $button.click(function () {
                    $button.button('loading');
                    let whitelabel = $('#whitelabel').val();
                    let provider = $('#provider').val();
                    let currency = $('#currency').val();
                    let route = `${$table.data('route')}/?whitelabel=${whitelabel}&provider=${provider}&currency=${currency}`;
                    api.ajax.url(route).load();
                    $table.on('draw.dt', function () {
                        $button.button('reset');
                    });
                });
                Reports.updatePercentage(api, $table.data('route'))
            }
        });

        $table.on('xhr.dt', function (event, settings, json, xhr) {
            if (xhr.status === 500 || xhr.status === 422) {
                swalError(xhr);
                $button.button('reset');
            }
        });
    };

    // Whitelabels totals report
    whitelabelsTotalsNew() {
        initDateRangePickerEndToday(open = 'right');
        let $table = $('#whitelabels-totals-table');
        let $button = $('#update');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.totals"
            },
            "order": [[0, "asc"]],
            "columns": [
                {"data": "whitelabel"},
                {"data": "provider"},
                {"data": "provider_type"},
                {"data": "currency_iso"},
                {"data": "played", "className": "text-right", "type": "num-fmt"},
                {"data": "won", "className": "text-right", "type": "num-fmt"},
                {"data": "profit", "className": "text-right", "type": "num-fmt"},
                {"data": "percentage", "className": "text-right", "type": "num-fmt"},
                {"data": "payment", "className": "text-right", "type": "num-fmt"},
                {"data": "rtp", "className": "text-right", "type": "num-fmt"},
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
            let currency = $('#currency').val();
            let provider = $('#provider').val();
            let whitelabel = $('#whitelabel').val();
            let convert = $('#convert').val();
            let route = `${$table.data('route')}/${startDate}/${endDate}?currency=${currency}&convert=${convert}&provider=${provider}&whitelabel=${whitelabel}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });

        $table.on('xhr.dt', function (event, settings, json, xhr) {
            if (xhr.status === 500 || xhr.status === 422) {
                swalError(xhr);
                $button.button('reset');
            }
        });

    };

    // Whitelabels totals report
    whitelabelsTotals() {
        initDateRangePickerEndToday(open = 'right');
        let $table = $('#whitelabels-totals-table');
        let $button = $('#update');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.totals"
            },
            "order": [[6, "desc"]],
            "columns": [
                {"data": "whitelabel"},
                {"data": "currency_iso"},
                {"data": "provider_type"},
                {"data": "provider"},
                {"data": "played", "className": "text-right", "type": "num-fmt"},
                {"data": "won", "className": "text-right", "type": "num-fmt"},
                {"data": "profit", "className": "text-right", "type": "num-fmt"},
                {"data": "rtp", "className": "text-right", "type": "num-fmt"},
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
            let currency = $('#currency').val();
            let provider = $('#provider').val();
            let whitelabel = $('#whitelabel').val();
            let route = `${$table.data('route')}/${startDate}/${endDate}?currency=${currency}&provider=${provider}&whitelabel=${whitelabel}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    };


    // Whitelabels daily
    whitelabelsSales() {
        initDateRangePickerEndToday(open = 'right');
        let $table = $('#whitelabels-sales-table');
        let $button = $('#update');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.sales"
            },
            "order": [[0, 'asc']],
            "columns": [
                {"data": "description"},
                {"data": "new_registers", "className": "text-right", "type": "num-fmt"},
                {"data": "unique_depositors", "className": "text-right", "type": "num-fmt"},
                {"data": "bonus", "className": "text-right", "type": "num-fmt"},
                {"data": "credit_approved", "className": "text-right", "type": "num-fmt"},
                {"data": "credit_rejected", "className": "text-right", "type": "num-fmt"},
                {"data": "debit_approved", "className": "text-right", "type": "num-fmt"},
                {"data": "debit_rejected", "className": "text-right", "type": "num-fmt"},
                {"data": "credit_manual", "className": "text-right", "type": "num-fmt"},
                {"data": "debit_manual", "className": "text-right", "type": "num-fmt"},
                {"data": "played", "className": "text-right", "type": "num-fmt"},
                {"data": "profit", "className": "text-right", "type": "num-fmt"},
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
}

window.Reports = Reports;
