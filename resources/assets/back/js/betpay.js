import {swalConfirm, swalError, swalSuccessWithButton} from "../../commons/js/core";
import {initSelect2, initLitepickerEndToday} from "./commons";
import moment from 'moment';

class BetPay {

    // accounts search
    accountsSearch() {
        initSelect2();
        let api;
        let $table = $('#betpay-users-search-table');
        let $button = $('#search');
        let $form = $('#betpay-search-form');

        $table.DataTable({
            "ajax": {
                "url": $form.attr('action'),
                "dataSrc": "data.accounts",
            },
            "order": [],
            "columns": [
                {"data": "users"},
                {"data": "username"},
                {"data": "info"},
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
    }

    // All clients
    all() {
        let $table = $('#clients-table');
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.clients"
            },
            "order": [],
            "columns": [
                {"data": "client"},
                {"data": "name"},
                {"data": "secret"},
                {"data": "endpoint"},
                {"data": "revoked", "className": "text-right"},
            ],
            "initComplete": function () {
                let api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });
    }

    // Account required
    accountRequired(){
        $(document).on('change', '#payments',function () {
            let status = $(this).find(':selected').data('account-required');
            let payment = $(this).val();
            if (status === 1) {
                switch (payment) {
                    case '1':
                    case '29':
                        $('.email-account').addClass('d-none');
                        $('.full-name').addClass('d-none');
                        $('.wire-transfers').removeClass('d-none');
                        $('.criptocurrency').addClass('d-none');
                        $('.alps').addClass('d-none');
                        $('.vcreditos_api').addClass('d-none');
                        break;
                    case '3':
                        $('.full-name').addClass('d-none');
                        $('.wire-transfers').addClass('d-none');
                        $('.email-account').addClass('d-none');
                        $('.criptocurrency').removeClass('d-none');
                        $('.alps').addClass('d-none');
                        $('.vcreditos_api').addClass('d-none');
                        break;
                    case '4':
                        $('.email-account').addClass('d-none');
                        $('.full-name').addClass('d-none');
                        $('.wire-transfers').addClass('d-none');
                        $('.criptocurrency').addClass('d-none');
                        $('.alps').removeClass('d-none');
                        $('.vcreditos_api').addClass('d-none');
                        break;
                    case '5':
                        $('.email-account').removeClass('d-none');
                        $('.full-name').removeClass('d-none');
                        $('.wire-transfers').addClass('d-none');
                        $('.criptocurrency').addClass('d-none');
                        $('.alps').addClass('d-none');
                        $('.vcreditos_api').addClass('d-none');
                        break;
                    case '7':
                    case '8':
                    case '9':
                    case '10':
                    case '11':
                        $('.email-account').removeClass('d-none');
                        $('.full-name').addClass('d-none');
                        $('.wire-transfers').addClass('d-none');
                        $('.criptocurrency').addClass('d-none');
                        $('.alps').addClass('d-none');
                        $('.vcreditos_api').addClass('d-none');
                        break;
                    case '35':
                        $('.email-account').addClass('d-none');
                        $('.full-name').addClass('d-none');
                        $('.wire-transfers').addClass('d-none');
                        $('.criptocurrency').addClass('d-none');
                        $('.alps').addClass('d-none');
                        $('.vcreditos_api').removeClass('d-none');
                        break;
                    default:
                        $('.email-account').addClass('d-none');
                        $('.full-name').addClass('d-none');
                        $('.wire-transfers').addClass('d-none');
                        $('.criptocurrency').addClass('d-none');
                        $('.alps').addClass('d-none');
                        break;
                }
            } else {
                $('.email-account').addClass('d-none');
                $('.full-name').addClass('d-none');
                $('.wire-transfers').addClass('d-none');
                $('.criptocurrency').addClass('d-none');
                $('.alps').addClass('d-none');
                $('.vcreditos_api').addClass('d-none');
            }

        });
    }

    // Banks data
    banksData(){
        initSelect2();
        $('.country').change(function () {
           let country = $(this).val();
           let route = $(this).data('route');
           let currency = $('#currency').val();
           let bank =  $('.bank');
           if (country !== '' && currency !== ''){
               $.ajax({
                   url: route,
                   type: 'get',
                   dataType: 'json',
                   data: {
                       country,
                       currency
                   }
               }).done(function (json) {
                   $(json.data.banks).each(function (key, element) {
                       bank.append("<option data-name='"+ element.name +"' value='"+ element.id +"'>" + element.name + "</option>");
                   })
                   bank.prop('disabled', false);
               }).fail(function (json) {

               });
           } else {

           }
        })
    }

    // Client Account
    clientAccount() {
        initSelect2();
        let $table = $('#client-account-list-table');
        let $button = $('#search');
        if ($.fn.DataTable.isDataTable('#client-account-list-table')) {
            $table.DataTable().destroy();
        }
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.accounts"
            },
            "order": [],
            "columns": [
                {"data": "client_name"},
                {"data": "currency_iso"},
                {"data": "name"},
                {"data": "status", "className": "text-right"},
                {"data": "details"},
                {"data": "action", "className": "text-right"}
            ],
            "initComplete": function () {
                let api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));

                $button.click(function () {
                    $button.button('loading');
                    let client = $('#client').val();
                    let currency = $('#currency').val();
                    let payments = $('#payments').val();
                    let route = `${$table.data('route')}?client=${client}&payment_method=${payments}&currency=${currency}`;
                    api.ajax.url(route).load();
                    $table.on('draw.dt', function () {
                        $button.button('reset');
                    });
                });
            }
        });

        $table.on('xhr.dt', function (event, settings, json, xhr) {
            if (xhr.status === 500) {
                swalError(xhr);
            }
        });
    }

     // Account required
     changeClientAccount(){
        $(document).on('change', '#payments',function () {
            let payment = $(this).val();
            switch (payment) {
                case '43':
                    $('.binance').removeClass('d-none');
                break;
            }
        });
    }

    // Credit report
    creditReport() {
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
                {"data": "amount", "className": "text-right", "type": "num-fmt"},
                {"data": "currency_iso"},
                {"data": "full_name"},
                {"data": "user_account.email"},
                {"data": "data.date", "className": "text-right"},
                {"data": "created", "className": "text-right"},
                {"data": "status", "className": "text-right"}
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
            let paymentMethod = $('#paymentMethod').val();
            let route = `${$table.data('route')}/${startDate}/${endDate}/${paymentMethod}?status=${status}`;
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

    // Debit transactions report
    debitTransactionsReport() {
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
                {"data": "amount", "className": "text-right", "type": "num-fmt"},
                {"data": "currency_iso"},
                {"data": "withdrawal_data"},
                {"data": "details"},
                {"data": "created", "className": "text-right"},
                {"data": "status", "className": "text-right"}
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
            let paymentMethod = $('#paymentMethod').val();
            let route = `${$table.data('route')}/${startDate}/${endDate}/${paymentMethod}?status=${status}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });

        $table.on('xhr.dt', function (event, settings, json, xhr) {
            $('#total').text(json.data.totals.total)
            if (xhr.status === 500) {
                swalError(xhr);
            }
        });
    }

    // Debit Uphold
    debitUphold() {
        let $table = $('#uphold-table');
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.transactions"
            },
            "order": [],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "level"},
                {"data": "amount", "className": "text-right", "type": "num-fmt"},
                {"data": "currency_iso"},
                {"data": "user_account.email"},
                {"data": "created", "className": "text-right"},
                {"data": "status", "className": "text-right"},
                {"data": "actions", "className": "text-right"}
            ],
            "initComplete": function () {
                let api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
                BetPay.lockBalance();
                BetPay.processDebitUphold(api, $table.data('route'));
            }
        });

        $table.on('xhr.dt', function (event, settings, json, xhr) {
            if (xhr.status === 500) {
                swalError(xhr);
            }
        });
    }

    // Lock balance
    static lockBalance() {
        $(document).on('click', '.lock-balance',function () {
            let $target = $(this);
            $target.button('loading');
            let user = $target.data('user');
            let wallet = $target.data('wallet');
            let amount = $target.data('amount');
            let provider = $target.data('provider');

            $.ajax({
                url: $target.data('route'),
                method: 'post',
                dataType: 'json',
                data: {user, wallet, amount, provider}

            }).done(function (json) {
                $target.button('reset');
                setTimeout(() => {
                    $($target).attr('disabled', true);
                }, 50);
                swalSuccessWithButton(json);

            }).fail(function (json) {
                $target.button('reset');
                swalError(json);
            });
        });
    }

    // Limit client
    limitClient() {
        initSelect2();
        let $table = $('#limit-client-table');
        let $button = $('#search');
        let api;

        if ($.fn.DataTable.isDataTable('#limit-client-table')) {
            $table.DataTable().destroy();
        }

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.client"
            },
            "order": [[0, "asc"]],
            "columns": [
                {"data": "name"},
                {"data": "payment_method"},
                {"data": "level"},
                {"data": "currency"},
                {"data": "transaction_type"},
                {"data": "min", "className": "text-right"},
                {"data": "max", "className": "text-right"},
                {"data": "status", "className": "text-right"},
                {"data": "actions", "className": "text-right"}
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
                BetPay.updateLimit(api)
            }
        });

        $button.click(function () {
            $button.button('loading');
            let client = $('#client').val();
            let currency = $('#currency').val();
            let transaction_type = $('#transaction_type').val();
            let payment = $('#payment').val();
            let route = `${$table.data('route')}?client=${client}&transaction_type=${transaction_type}&payment_method=${payment}&currency=${currency}`;
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

    // Process credit transactions
    static processCreditTransactions(api, route) {
        initSelect2();
        let $button = $('#process-credit');
        let $form = $('#process-credit-form')
        let $modal = $('#process-credit-modal');

        $button.click(function () {
            $button.button('loading');
            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                dataType: 'json',
                data: $form.serialize()

            }).done(function (json) {
                $form.trigger('reset');
                $('#action').val(null).trigger('change');
                $modal.modal('hide');
                swalSuccessWithButton(json);
                api.ajax.url(route).load();

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });

        $modal.on('show.bs.modal', function (event) {
            let $target = $(event.relatedTarget);
            $('#transaction').val($target.data('transaction'));
            $('#wallet').val($target.data('wallet'));
            $('#user').val($target.data('user'));
        })
    }

    // Store client
    storeClient() {
        initSelect2();
        let $form = $('#clients-form');
        let $button = $('#save');

        $button.click(function () {
            $button.button('loading');
            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                dataType: 'json',
                data: $form.serialize()
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

    // Store client and payment method
    storeClientsPaymentMethod() {
        initSelect2();
        let $form = $('#clients-payment-methods-form');
        let $button = $('#save');
        let $bank = $('.bank');
        let $bank_name;

        $button.click(function () {
            $button.button('loading');
            $bank_name = $bank.find(':selected').data('name');
            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                dataType: 'json',
                data: $form.serialize() + '&bank_name=' + $bank_name
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

    //storeAccountClient
    storeAccountClient(){
        initSelect2();
        initFileInput();
        let $form = $('#client-account-form');
        let $button = $('#save');

        $button.click(function () {
            $button.button('loading');
            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                dataType: 'json',
                data: $form.serialize()

            }).done(function (json) {
                swalSuccessWithButton(json);
                $('save-form').trigger('reset');
            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }

    // Update client
    updateClient() {
        initSelect2();
        let $form = $('#clients-form');
        let $button = $('#update');

        $button.click(function () {
            $button.button('loading');
            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                dataType: 'json',
                data: $('#clients-form').serialize()

            }).done(function (json) {
                swalSuccessWithButton(json);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }

    // Update client account
    updateClientAccount() {
        initSelect2();
        let $form = $('#client-account-form');
        let $button = $('#update');
        let $bank = $('.bank');
        let $bank_name;

        $button.click(function () {
            $button.button('loading');
            $bank_name = $bank.find(':selected').data('name');
            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                dataType: 'json',
                data: $form.serialize() + '&bank_name=' + $bank_name
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

    // Update client limit
    static updateLimit(api) {
        initSelect2();
        let $button = $('#update');
        let $form = $('#update-limit-form');
        let $modal = $('#update-limit');
        let $table = $('#limit-client-table');

        $modal.on('show.bs.modal', function(event) {
            let $target = $(event.relatedTarget);
            $('#min').val($target.data('min'));
            $('#max').val($target.data('max'));
            $('#transaction-type').val($target.data('transaction-type'));
            let status = $target.data('status');
            let level = $target.data('level');
            let currency = $target.data('currency');
            let client_payment = $target.data('client-payment');
            $('#status').find('option[value="'+ status +'"]').prop('selected', true).trigger("change");
            $button.click(function () {
                $button.button('loading');
                $.ajax({
                    url: $form.attr('action'),
                    method: 'post',
                    data: $form.serialize() + '&currency=' + currency + '&client=' + client_payment + '&level=' + level
                }).done(function (json) {
                    api.ajax.url($table.data('route')).load();
                    swalSuccessWithButton(json);
                    $modal.modal('hide');
                }).fail(function (json) {
                    swalError(json);
                }).always(function () {
                    $button.button('reset');
                });
            });
        })
    }

    // Credit binance
    creditBinance() {
        let $table = $('#binance-table');
        let $modal = $('#watch-binance-qr-modal');

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.transactions"
            },
            "order": [],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "level"},
                {"data": "currency_iso"},
                {"data": "amount", "className": "text-right", "type": "num-fmt"},
                {"data": "cryptocurrency"},
                {"data": "crypto_amount", "className": "text-right", "type": "num-fmt"},
                {"data": "origin_account"},
                {"data": "data.date", "className": "text-right"},
                {"data": "created", "className": "text-right"},
                {"data": "status", "className": "text-right"},
                {"data": "actions", "className": "text-right"}
            ],
            "initComplete": function () {
                let api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
                BetPay.lockBalance();
                BetPay.processCreditTransactions(api, $table.data('route'));
            }
        });

        $modal.on('show.bs.modal', function (event) {
            let $target = $(event.relatedTarget);
            $('#qr').append($target.data('qr'));
        })

        $modal.on('hidden.bs.modal', function () {
            $modal.find('#qr').html('');
        })

        $table.on('xhr.dt', function (event, settings, json, xhr) {
            if (xhr.status === 500) {
                swalError(xhr);
            }
        });
    }

    // Debit Binance
    debitBinance() {
        let $table = $('#binance-table');
        let $modal = $('#watch-binance-qr-modal');
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.transactions"
            },
            "order": [],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "level"},
                {"data": "currency_iso"},
                {"data": "amount", "className": "text-right", "type": "num-fmt"},
                {"data": "cryptocurrency"},
                {"data": "crypto_amount", "className": "text-right", "type": "num-fmt"},
                {"data": "payment_method"},
                {"data": "created", "className": "text-right"},
                {"data": "status", "className": "text-right"},
                {"data": "actions", "className": "text-right"}
            ],
            "initComplete": function () {
                let api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
                BetPay.lockBalance();
                BetPay.processDebitBinance(api, $table.data('route'));
            }
        });

        $modal.on('show.bs.modal', function (event) {
            let $target = $(event.relatedTarget);
            $('#qr').append($target.data('qr'));
        })

        $modal.on('hidden.bs.modal', function () {
            $modal.find('#qr').html('');
        })

        $table.on('xhr.dt', function (event, settings, json, xhr) {
            if (xhr.status === 500) {
                swalError(xhr);
            }
        });
    }

    // Process debit binance
    static processDebitBinance(api, route) {
        initSelect2();
        let $button = $('#process-debit');
        let $form = $('#process-debit-form');
        let $modal = $('#process-debit-modal');

        $button.click(function () {
            $button.button('loading');
            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                dataType: 'json',
                data: $form.serialize()

            }).done(function (json) {
                $form.trigger('reset');
                $('#action').val(null).trigger('change');
                $modal.modal('hide');
                swalSuccessWithButton(json);
                api.ajax.url(route).load();

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });

        $modal.on('show.bs.modal', function (event) {
            let $target = $(event.relatedTarget);
            $('#transaction').val($target.data('transaction'));
            $('#wallet').val($target.data('wallet'));
            $('#user').val($target.data('user'));
        })
    }
}

window.BetPay = BetPay;
