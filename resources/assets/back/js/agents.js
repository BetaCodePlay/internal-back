import {jstree} from 'jstree';
import {
    clipboard,
    swalConfirm,
    swalError,
    swalSuccessWithButton,
    swalSuccessNoButton,
    swalInput
} from "../../commons/js/core";
import {clearForm, getCookie, initDateRangePickerEndToday, initLitepickerEndToday,initLitepickerEndTodayNew, initSelect2, refreshRandomPassword} from "./commons";
import moment from 'moment';

class Agents {

    // Add users
    addUsers() {
        initSelect2();
        let $form = $('#add-users-form');
        let $button = $('#update');

        $button.click(function () {
            $button.button('loading');
            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                dataType: 'json',
                data: {
                    username: $('#username').val(),
                    agent: $('#agent').val()
                }

            }).done(function (json) {
                $form.trigger('reset');
                swalSuccessWithButton(json);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }

    // Agents sub agents
    agents() {
        $('#agents-tab').on('show.bs.tab', function () {
            let $table = $('#agents-table');
            let user = $('.user').val();
            let $route = $table.data('route') + '/' + user;
            if ($.fn.DataTable.isDataTable('#agents-table')) {
                $table.DataTable().destroy();
            }

            $table.DataTable({
                "ajax": {
                    "url": $route,
                    "dataSrc": "data.agents"
                },
                "order": [],
                "columns": [
                    {"data": "username"},
                    {"data": "type"},
                    {"data": "percentages", "className": "text-right", "type": "num-fmt"},
                    {"data": "balance", "className": "text-right", "type": "num-fmt"},
                    {"data": "actions", "className": "text-right"}
                ],
                "initComplete": function () {
                    let api = this.api();
                    api.buttons().container()
                        .appendTo($('#table-buttons-agents'));
                    Agents.updatePercentage(api, $route)
                }
            });
        });
    }

    // Agents balances
    agentsBalances() {
        let $table = $('#agents-balances-table');
        let $button = $('#update');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.agents"
            },
            "order": [[1, 'desc']],
            "columns": [
                {"data": "username"},
                {"data": "balance", "className": "text-right", "type": "num-fmt"}
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

        $table.on('xhr.dt', function (event, setting, json) {
            $('#total_balances').text(json.data.total_balances);
        });
    };

    // Agents transactions
    agentsTransactions() {
        $('#agents-transactions-tab').on('show.bs.tab', function () {
            let $table = $('#agents-transactions-table');
            let user = $('.user').val();
            let api;

            if ($.fn.DataTable.isDataTable('#agents-transactions-table')) {
                $table.DataTable().destroy();
            }

            $table.DataTable({
                "ajax": {
                    "url": $table.data('route') + '/' + user,
                    "dataSrc": "data.transactions"
                },
                "order": [],
                "lengthMenu":[20,50,100],
                "columns": [
                    {"data": "date"},
                    {"data": "data.from"},
                    {"data": "data.to"},
                    {"data": "debit", "className": "text-right", "type": "num-fmt"},
                    {"data": "credit", "className": "text-right", "type": "num-fmt"},
                    {"data": "balance", "className": "text-right", "type": "num-fmt"}
                ],
                "initComplete": function () {
                    api = this.api();
                    api.buttons().container()
                        .appendTo($('#table-buttons-agents-transactions'));
                }
            });
        });
    }

    // Agents Transactions Paginate
    agentsTransactionsPaginate(lengthMenu,user =null) {
        $('#agents-transactions-tab').on('show.bs.tab', function () {

               let $tableTransaction = $('#agents-transactions-table');
               let $button = $('#updateNew');
               let picker = initLitepickerEndTodayNew();
               let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
               let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');

               if (user == null) {
                   user = $('.user').val();
                }
               let api;

               $tableTransaction.DataTable({
                   destroy: true,
                   processing: true,
                   serverSide: true,
                   lengthMenu:lengthMenu,
                   ajax: {
                       url: $tableTransaction.data('route') + '/' + user+'?startDate='+startDate+'&endDate='+endDate,
                       dataType: 'json',
                       type: 'get',
                   },
                   columns: [
                       {"data": "date"},
                       {"data": "data.from"},
                       {"data": "data.to"},
                       {"data": "debit", "className": "text-right", "type": "num-fmt"},
                       {"data": "credit", "className": "text-right", "type": "num-fmt"},
                       {"data": "balance", "className": "text-right", "type": "num-fmt"}
                   ],
                   initComplete: function () {
                       api = this.api();
                       api.buttons().container()
                           .appendTo($('#table-buttons'));
                   }
               });

               Agents.agentsTransactionsPaginateTotal($tableTransaction.data('routetotals'),user,startDate,endDate)

               $button.click(function () {
                   $button.button('loading');
                   let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
                   let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');
                   let route = `${$tableTransaction.data('route')}/${user}?startDate=${startDate}&endDate=${endDate}`;
                   api.ajax.url(route).load();
                   $tableTransaction.on('draw.dt', function () {
                       $button.button('reset');
                   });
                   Agents.agentsTransactionsPaginateTotal($tableTransaction.data('routetotals'),user,startDate,endDate)

               });

        });
    }
    // Agents Transactions Paginate Total
    static agentsTransactionsPaginateTotal(url_total,user,start_date,end_date) {
        $.ajax({
            url: url_total+'/'+user+'?startDate='+start_date+'&endDate='+end_date,
            type: 'get',
        }).done(function (response) {
            $('.totalsTransactionsPaginate').empty();
            $('.totalsTransactionsPaginate').append(response)
        });
    }

    // Agents transactions by dates
    agentsTransactionsByDates() {
        initSelect2();
        let picker = initLitepickerEndToday();
        let $table = $('#agents-transactions-table');
        let $button = $('#update');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.transactions"
            },
            "order": [[0, "asc"]],
            "columns": [
                {"data": "username"},
                {"data": "debit", "className": "text-right", "type": "num-fmt"},
                {"data": "credit", "className": "text-right", "type": "num-fmt"},
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
            let route = `${$table.data('route')}/${startDate}/${endDate}`;
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
    }

    // Agents payments
    agentsPayments(){
        console.log('agent payments.jd')
        let picker = initLitepickerEndToday();
        let $table = $('#agent-payment-transactions-table');
        let $button = $('#update');
        let api;
        clipboard();
        let $tree = $('#tree');
        $tree.jstree({
            'core': {
                'data': $('#tree').data('json')
            }
        });

        $tree.on('changed.jstree', function (event, data) {
            let id;
            let type;
            let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
            let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');

            if (data.action === 'select_node') {
                id = data.selected[0];
                type = data.node.li_attr.data_type;

            } else {
                id = data.selected[0];
                type = 'agent';
            }
            console.log('agents', id, type);
            if (id !== undefined) {

                // $.ajax({
                //     url: $tree.data('route'),
                //     type: 'get',
                //     dataType: 'json',
                //     data: {
                //         startDate, endDate, id, type
                //     }
                // }).done(function (json) {

                // }).fail(function (json) {
                //     swalError(json);
                // });

                $table.DataTable({
                    "ajax": {
                        "url": `${$table.data('route')}/${startDate}/${endDate}/${id}`,
                        "dataSrc": "data.payments"
                    },
                    "order": [],
                    "columns": [
                        {"data": "username"},
                        {"data": "loads"},
                        {"data": "downloads"},
                        {"data": "total"},
                        {"data": "commission"},
                        {"data": "payment"},
                        {"data": "receivable"},
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
                    let route = `${$table.data('route')}/${startDate}/${endDate}/${id}`;
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
        })
    }

    // Cash flow by dates
    cashFlowByDates() {
        let picker = initLitepickerEndToday();
        let $table = $('#cash-flow-transactions-table');
        let $button = $('#update');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.financial"
            },
            "order": [[1, "asc"]],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "initial_balance", "className": "text-right", "type": "num-fmt"},
                {"data": "debit", "className": "text-right", "type": "num-fmt"},
                {"data": "credit", "className": "text-right", "type": "num-fmt"},
                {"data": "final_balance", "className": "text-right", "type": "num-fmt"}
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
            $('#credit').text(json.data.totals.credit);
            $('#debit').text(json.data.totals.debit);
        });
    }

    // Change agent type
    changeAgentType() {
        $(document).on('click', '#change-agent-type', function () {
            let route = $(this).data('route');
            let tree = $('#tree');
            swalConfirm(route, function () {
                tree.jstree(true).refresh();
                tree.trigger('loaded.jstree');
            });
        });
    }

    // Change user status
    changeUserStatus() {
        $(document).on('click', '#change-user-status', function () {
            let description = $('#description').val();
            let route = $(this).data('route');
            swalInput(route);
        });
    }

    // Dashboard
    dashboard() {
        //console.log('test in dashboard fo agent.jd')

        initSelect2();
        clipboard();
        let $tree = $('#tree');
        $tree.jstree({
            'core': {
                'data': $('#tree').data('json')
            }
        });

        $tree.on('changed.jstree', function (event, data) {
            $('#dashboard-tab').tab('show');
            $('#option_country').addClass('d-none');
            $('#option_timezone_agent').addClass('d-none');
            $('#option_timezone_user').addClass('d-none');
            $('#option_email').addClass('d-none');
            $('#option_percentage').addClass('d-none');
            $('#option_currencies').addClass('d-none');

            let id;
            let type;

            if (data.action === 'select_node') {
                id = data.selected[0];
                type = data.node.li_attr.data_type;

            } else {
                id = data.selected[0];
                type = 'agent';
            }
            if (id !== undefined) {

                $.ajax({
                    url: $tree.data('route'),
                    type: 'get',
                    dataType: 'json',
                    data: {
                        id, type
                    }

                }).done(function (json) {
                    $('#username').text(json.data.user.username);
                    $('#agent_timezone').text(json.data.user.timezone);
                    $('.balance').text(json.data.balance);
                    $('.balanceAuth_'+json.data.user.id).text('');
                    $('.balanceAuth_'+json.data.user.id).text(json.data.balance);
                    $('#user_type').html(json.data.user.type);
                    $('#status').html(json.data.user.status);
                    $('#wallet').val(json.data.wallet);
                    $('.wallet').val(json.data.wallet);
                    $('.user').val(id);
                    $('#name').val(json.data.user.username);
                    $('#type').val(json.data.type);
                    $('.type').val(json.data.type);
                    $('#referral_code').text(json.data.user.referral_code);
                    $('.clipboard').attr('data-clipboard-text', json.data.user.url);

                    if (json.data.master) {
                        $('#agents-tab').removeClass('d-none');
                        $('#agents-mobile').removeClass('d-none');
                        $('#move-agents').removeClass('d-none');
                    } else {
                        $('#agents-tab').addClass('d-none');
                        $('#agents-mobile').addClass('d-none');
                        $('#move-agents').addClass('d-none');
                    }

                    if (json.data.agent) {
                        $('#users-tab').removeClass('d-none');
                        $('#agents-transactions-tab').removeClass('d-none');
                        $('#financial-state-tab').removeClass('d-none');
                        $('#users-transactions-tab').addClass('d-none');
                        $('#users-mobile').removeClass('d-none');
                        $('#agents-transactions-mobile').removeClass('d-none');
                        $('#financial-state-mobile').removeClass('d-none');
                        $('#users-transactions-mobile').addClass('d-none');
                        $('#move-agents-user').addClass('d-none');
                        $('#move-agents').removeClass('d-none');
                    } else {
                        $('#users-tab').addClass('d-none');
                        $('#agents-transactions-tab').addClass('d-none');
                        $('#financial-state-tab').addClass('d-none');
                        $('#users-transactions-tab').removeClass('d-none');
                        $('#users-mobile').addClass('d-none');
                        $('#agents-transactions-mobile').addClass('d-none');
                        $('#financial-state-mobile').addClass('d-none');
                        $('#users-transactions-mobile').removeClass('d-none');
                        $('#move-agents-user').removeClass('d-none');
                        $('#move-agents').addClass('d-none');
                    }

                    if (json.data.myself) {
                        if(!json.data.agent_player){
                            $('#new-user, #new-agent').addClass('d-none');
                        }else {
                            $('#new-user, #new-agent').removeClass('d-none');
                        }
                        $('#locks, #locks-tab').addClass('d-none');
                        $('#locks, #locks-mobile').addClass('d-none');
                        $('#transactions-form-container').addClass('d-none');
                        $('#modals-transaction').addClass('d-none');
                        $('#move-agents-user').addClass('d-none');
                        $('#move-agents').addClass('d-none');
                    } else {
                        $('#new-user, #new-agent').addClass('d-none');
                        $('#locks, #locks-tab').removeClass('d-none');
                        $('#locks, #locks-mobile').removeClass('d-none');
                        $('#transactions-form-container').removeClass('d-none');
                        $('#modals-transaction').removeClass('d-none');
                    }

                }).fail(function (json) {
                    swalError(json);
                });
            }
        })
    }

    //Deposits withdrawals provider
   depositsWithdrawalsByProvider() {
       initDateRangePickerEndToday(open = 'right');
        initSelect2();
        let $table = $('#deposits-whithdrawal-providers-table');
        let $button = $('#search');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.agents"
            },
            "order": [[0, 'asc']],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "amount", "className": "text-right", "type": "num-fmt"},
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
            let transaction_type = $('#transaction_type').val();
            let startDate = $('#start_date').val();
            let endDate = $('#end_date').val();
            let route = `${$table.data('route')}?start_date=${startDate}&end_date=${endDate}&currency=${currency}&transaction_type=${transaction_type}`;
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

   // Financial state
   financialState(user = null) {
       $('#financial-state-tab').on('show.bs.tab', function () {

       })

        let picker = initLitepickerEndToday();
        let $table = $('#financial-state-table');
        let $button = $('#update');
        $button.trigger('click')
        let api;
        if (user == null) {
            $('#financial-state-tab').on('show.bs.tab', function () {
                $table.children().remove();
                user = $('.user').val();
            });
        }

        $button.click(function () {
            $button.button('loading');
            let username_like = $('#username_like').val() === ''?'':'&username_like='+$('#username_like').val();
            let provider_id = $('#provider_id').val() === ''?'':'&provider_id='+$('#provider_id').val();
            let test = '?test=false'
            let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
            let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');

            $.ajax({
                url: `${$table.data('route')}/${user}/${startDate}/${endDate}${test}${username_like}${provider_id}`,
                type: 'get',
                dataType: 'json'

            }).done(function (json) {
                $table.html(json.data.table);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }

  financialStateDetails(user = null) {
        let picker = initLitepickerEndToday();
        let $table = $('#financial-state-table');
        let $button = $('#update');
        let api;
        if (user == null) {
            $('#financial-state-tab').on('show.bs.tab', function () {
                $table.children().remove();
                user = $('.user').val();
            });
        }

        $button.click(function () {
            $button.button('loading');
            let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
            let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');

            $.ajax({
                url: `${$table.data('route')}/${user}/${startDate}/${endDate}`,
                type: 'get',
                dataType: 'json'

            }).done(function (json) {
                $table.html(json.data.table);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }

    //Lock providers
    lockProvider() {
        initSelect2();
        let $form_agent = $('#lock-agent-form');
        let $form_user = $('#lock-user-form');
        let $buttonLock = $('#lock-agent');
        let $buttonUnlock = $('#unlock-agent');
        let $buttonLockUsers = $('#lock-users');
        let $buttonUnlockUsers = $('#unlock-users');

        $buttonLock.click(function () {
            $buttonLock.button('loading');
            let type = 'true';
            let lock_users = 'false';

            $.ajax({
                url: $form_agent.attr('action'),
                type: 'post',
                dataType: 'json',
                data: $form_agent.serialize()  + '&type=' + type + '&lock_users=' + lock_users
            }).done(function (json) {
                $form_agent.trigger('reset');
                $('#provider').val('').trigger('change');
                swalSuccessWithButton(json);
            }).fail(function (json) {
                swalError(json);
            }).always(function () {
                $buttonLock.button('reset');
                $buttonUnlock.button('reset');
            });
        });

        $buttonUnlock.click(function () {
            $buttonUnlock.button('loading');
            let type = 'false';
            let lock_users = 'false';

            $.ajax({
                url: $form_agent.attr('action'),
                type: 'post',
                dataType: 'json',
                data: $form_agent.serialize()  + '&type=' + type + '&lock_users=' + lock_users
            }).done(function (json) {
                $form_agent.trigger('reset');
                $('#provider').val(null).trigger('change');
                swalSuccessWithButton(json);
            }).fail(function (json) {
                swalError(json);
            }).always(function () {
                $buttonLock.button('reset');
                $buttonUnlock.button('reset');
            });
        });

        $buttonLockUsers.click(function () {
            $buttonLockUsers.button('loading');
            let type = 'true';
            let lock_users = 'true';

            $.ajax({
                url: $form_user.attr('action'),
                type: 'post',
                dataType: 'json',
                data: $form_user.serialize()  + '&type=' + type + '&lock_users=' + lock_users
            }).done(function (json) {
                $form_user.trigger('reset');
                swalSuccessWithButton(json);
            }).fail(function (json) {
                swalError(json);
            }).always(function () {
                $buttonLockUsers.button('reset');
                $buttonUnlockUsers.button('reset');
            });
        });

        $buttonUnlockUsers.click(function () {
            $buttonUnlockUsers.button('loading');
            let type = 'false';
            let lock_users = 'true';

            $.ajax({
                url: $form_user.attr('action'),
                type: 'post',
                dataType: 'json',
                data: $form_user.serialize()  + '&type=' + type + '&lock_users=' + lock_users
            }).done(function (json) {
                $form_user.trigger('reset');
                swalSuccessWithButton(json);
            }).fail(function (json) {
                swalError(json);
            }).always(function () {
                $buttonLockUsers.button('reset');
                $buttonUnlockUsers.button('reset');
            });
        });

        $form_agent.keypress(function(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
            }
        });

        $form_user.keypress(function(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
            }
        });
    }

   // Main agents
   mainAgents() {
        initSelect2();
        let $form = $('#main-agents-form');
        let $button = $('#save');
       clearForm($form);

        $button.click(function () {
            $button.button('loading');
            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                dataType: 'json',
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

    // Move agent user
    moveAgentUser(){
        initSelect2();
        let $form = $('#move-agent-user-form');
        let $button = $('#move-user-button');
        clearForm($form);

        $button.click(function () {
            $button.button('loading');
            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                dataType: 'json',
                data: $form.serialize()

            }).done(function (json) {
                $form.trigger('reset');
                $('#users_agent').val(null).trigger('change');
                swalSuccessWithButton(json);
                setTimeout(() => window.location.href = '', 1500);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });

        $form.keypress(function(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
            }
        });
    }

    // Move agent
    moveAgent(){
        initSelect2();
        let $form = $('#move-agent-form');
        let $button = $('#move-agent-button');
        clearForm($form);

        $button.click(function () {
            $button.button('loading');
            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                dataType: 'json',
                data: $form.serialize()

            }).done(function (json) {
                $form.trigger('reset');
                $('#users_agent').val(null).trigger('change');
                swalSuccessWithButton(json);
                setTimeout(() => window.location.href = '', 1500);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });

        $form.keypress(function(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
            }
        });
    }

    // Manual transactions modal
    manualTransactionsModal() {
        let $button = $('#transactions-button');
        let $form = $('#transactions-modal-form');
        let $modal = $('#transaction-modal');

        $modal.on('show.bs.modal', function (event) {
            let $target = $(event.relatedTarget);
            let transactionType = $target.data('transaction-type')
            let name = $target.data('transaction-name');
            $('.manual-transaction-type').text(name);
            $('.transaction_type').val(parseInt(transactionType));
        });

        $button.click(function () {
            $button.button('loading');
            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                data: $form.serialize()
            }).done(function (json) {
                $('.balance').text(json.data.balance);
                $form.trigger('reset');
                $('#transaction-modal').modal('hide');
                swalSuccessWithButton(json);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }

    // Manual transactions
    manualTransactions() {
        initDateRangePickerEndToday(open = 'right');
        initSelect2();
        let $table = $('#manual-transactions-table');
        let $button = $('#search');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.transactions"
            },
            "order": [[0, 'desc']],
            "columns": [
                {"data": "date"},
                {"data": "data.from"},
                {"data": "data.to"},
                {"data": "debit", "className": "text-right", "type": "num-fmt"},
                {"data": "credit", "className": "text-right", "type": "num-fmt"},
                {"data": "balance", "className": "text-right", "type": "num-fmt"}
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
            let route = `${$table.data('route')}/${startDate}/${endDate}/${currency}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    }

    // Menul mobile
    menuMobile() {
        $('[data-target]').click(function() {
            const target = $(this).data('target');
            $(target).trigger('click');
            switch (target) {
                case '#dashboard':
                    $('.mobile').removeClass('active');
                    $('#dashboard-tab').tab('show');
                    $(target).addClass('active');
                    break;

                case '#agents-transactions':
                    $('.mobile').removeClass('active');
                    $('#agents-transactions-tab').tab('show');
                    $(target).addClass('active');
                    break;

                case '#users-transactions':
                    $('.mobile').removeClass('active');
                    $('#users-transactions-tab').tab('show');
                    $(target).addClass('active');
                    break;

                case '#users':
                    $('.mobile').removeClass('active');
                    $('#users-tab').tab('show');
                    $(target).addClass('active');
                    break;

                case '#agents':
                    $('.mobile').removeClass('active');
                    $('#agents-tab').tab('show');
                    $(target).addClass('active');
                    break;

                case '#financial-state':
                    $('.mobile').removeClass('active');
                    $('#financial-state-tab').tab('show')
                    $(target).addClass('active');
                    break;

                case '#locks':
                    $('.mobile').removeClass('active');
                    $('#locks-tab').tab('show');
                    $(target).addClass('active');
                    break;
            }
        });
    }

    //Options form agent
    optionsFormAgent() {
        initSelect2();
        let $checkbox = $('#show_data_agent');
        $checkbox.click(function () {
            if ($(this).prop('checked') == true) {
                $('.option_data_agent').removeClass('d-none');
            } else {
                $('.option_data_agent').addClass('d-none');
            }
        });
    }

    //Options form user
    optionsFormUser() {
        initSelect2();
        let $checkbox = $('#show_data_user');
        $checkbox.click(function () {
            if ($(this).prop('checked') == true) {
                $('.option_data_user').removeClass('d-none');
            } else {
                $('.option_data_user').addClass('d-none');
            }
        });
    }

    // Perform transactions
    performTransactions() {
        let $form = $('#transactions-form');
        let $credit = $('#credit');
        let $debit = $('#debit');

        $credit.click(function () {
            $credit.button('loading');
            $debit.button('loading');
            let transactionType = 1;

            $.ajax({
                url: $form.attr('action'),
                type: 'post',
                dataType: 'json',
                data: $form.serialize() + '&transaction_type=' + transactionType

            }).done(function (json) {
                $('.balance').text(json.data.balance);
                //$('#ticket').html('').append(json.data.button);
                $form.trigger('reset');
                swalSuccessWithButton(json);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $credit.button('reset');
                $debit.button('reset');
            });
        });

        $debit.click(function () {
            $credit.button('loading');
            $debit.button('loading');
            let transactionType = 2;

            $.ajax({
                url: $form.attr('action'),
                type: 'post',
                dataType: 'json',
                data: $form.serialize() + '&transaction_type=' + transactionType

            }).done(function (json) {
                $('.balance').text(json.data.balance);
                //$('#ticket').html('').append(json.data.button);
                $form.trigger('reset');
                swalSuccessWithButton(json);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $credit.button('reset');
                $debit.button('reset');
            });
        })

        $form.keypress(function(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
            }
        });
    }

    //provider currency
    providerCurrency(){
        let $currency = $('#currency');
        $.get('provider-currency/'+$currency.val(), function(data){
            $.each(data.data, function(key, element) {
                $('#provider').append("<option value="+ element.id + ">" + element.name + "</option>");
            });
        });
    }

    //provider lcok data
    providerLockData(){
        initSelect2();
        let $table = $('#locked-providers-table');
        let $button = $('#update');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.agents"
            },
            "order": [[0, 'asc']],
            "columns": [
                {"data": "agent"},
                {"data": "provider"},
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
            let provider = $('#provider').val();
            let route = `${$table.data('route')}?currency=${currency}&provider=${provider}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    }

    //Relocation agents
    relocationAgents() {
        let $modal = $('#move-agents-modal');
        $modal.on('show.bs.modal', function (event) {
            let user = $('.user').val();
            let agents = $('#relocation-agents');
            let route = `${agents.data('route')}/${user}`;
            $.get(route, function(json){
                $('#relocation-agents option[value!=""]').remove();
                $.each(json.data.agents, function(key, element) {
                    agents.append("<option value="+ element.id + ">" + element.username + "</option>");
                });
            });
        });
    }

    // Store agents
    storeAgents() {
        initSelect2();
        let $form = $('#create-agents-form');
        let $button = $('#create-agent');

        $button.click(function () {
            $button.button('loading');

            $.ajax({
                url: $form.attr('action'),
                type: 'post',
                dataType: 'json',
                data: $form.serialize()

            }).done(function (json) {
                $('.balance').text(json.data.balance);
                $form.trigger('reset');
                $('#timezone').val('').trigger('change');
                $('#add-agents-modal').modal('hide');
                swalSuccessNoButton(json);
                setTimeout(() => {
                    window.location.href = json.data.route;
                }, 1000);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }

    //Search agent
    searchAgent(placeholder){
        $('select').select2();
        let $search_agent = $('#search_agent');

        $search_agent.select2({
            width: '100%',
            placeholder,
            allowClear: true,
            language: 'es',
            id: search_agent.id || search_agent.id,
            text: search_agent.text || search_agent.username,

            ajax: {
                type: "POST",
                url: $search_agent.data('route'),
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

                    $.each(json.data.agents, function (usersIndex, value) {
                        results.push({
                            id: value.id,
                            text: value.username,
                            type: value.type
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
                return repo.username || repo.text;
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

    // Search user dashboard
    searchAgentDashboard() {
        initSelect2();
        clipboard();
        $('.agent_id_search').change('select2:selecting',function (e) {
            $('#dashboard-tab').tab('show');
            $('#option_country').addClass('d-none');
            $('#option_timezone').addClass('d-none');
            $('#option_email').addClass('d-none');
            $('#option_percentage').addClass('d-none');
            $('#option_currencies').addClass('d-none');
            let id = $(this).val();
            $.ajax({
                url: $(this).data('select'),
                type: 'get',
                dataType: 'json',
                data: {
                    id
                }

            }).done(function (json) {
                $('#username').text(json.data.user.username);
                $('#agent_timezone').text(json.data.user.timezone);
                $('.balance').text(json.data.balance);
                $('#user_type').html(json.data.user.type);
                $('#status').html(json.data.user.status);
                $('#wallet').val(json.data.wallet);
                $('.wallet').val(json.data.wallet);
                $('.user').val(id);
                $('#name').val(json.data.user.username);
                $('#type').val(json.data.type);
                $('.type').val(json.data.type);
                $('#referral_code').text(json.data.user.referral_code);
                $('.clipboard').attr('data-clipboard-text', json.data.user.url);

                if (json.data.master) {
                    $('#agents-tab').removeClass('d-none');
                    $('#agents-mobile').removeClass('d-none');
                } else {
                    $('#agents-tab').addClass('d-none');
                    $('#agents-mobile').addClass('d-none');
                }

                if (json.data.agent) {
                    $('#users-tab').removeClass('d-none');
                    $('#agents-transactions-tab').removeClass('d-none');
                    $('#financial-state-tab').removeClass('d-none');
                    $('#users-transactions-tab').addClass('d-none');
                    $('#users-mobile').removeClass('d-none');
                    $('#agents-transactions-mobile').removeClass('d-none');
                    $('#financial-state-mobile').removeClass('d-none');
                    $('#users-transactions-mobile').addClass('d-none');
                    $('#move-agents-user').addClass('d-none');
                    $('#move-agents').removeClass('d-none');
                } else {
                    $('#users-tab').addClass('d-none');
                    $('#agents-transactions-tab').addClass('d-none');
                    $('#financial-state-tab').addClass('d-none');
                    $('#users-transactions-tab').removeClass('d-none');
                    $('#users-mobile').addClass('d-none');
                    $('#agents-transactions-mobile').addClass('d-none');
                    $('#financial-state-mobile').addClass('d-none');
                    $('#users-transactions-mobile').removeClass('d-none');
                    $('#move-agents-user').removeClass('d-none');
                    $('#move-agents').addClass('d-none');
                }

                if (json.data.myself) {
                    if(!json.data.agent_player){
                        $('#new-user, #new-agent').addClass('d-none');
                    }else {
                        $('#new-user, #new-agent').removeClass('d-none');
                    }
                    $('#locks, #locks-tab').addClass('d-none');
                    $('#locks, #locks-mobile').addClass('d-none');
                    $('#transactions-form-container').addClass('d-none');
                    $('#modals-transaction').addClass('d-none');
                    $('#move-agents-user').addClass('d-none');
                } else {
                    $('#new-user, #new-agent').addClass('d-none');
                    $('#locks, #locks-tab').removeClass('d-none');
                    $('#locks, #locks-mobile').removeClass('d-none');
                    $('#transactions-form-container').removeClass('d-none');
                    $('#modals-transaction').removeClass('d-none');
                }
            }).fail(function (json) {
                swalError(json);
            });
        })
    }


    // Select agent or user
    selectAgentOrUser(placeholder) {
        $('select').select2();
        let $agent_id_search = $('#agent_id_search');

        $agent_id_search.select2({
            width: '100%',
            placeholder,
            allowClear: true,
            language: 'es',
            id: agent_id_search.id || agent_id_search.id,
            text: agent_id_search.text || agent_id_search.username,

            ajax: {
                type: "POST",
                url: $agent_id_search.data('route'),
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

                    $.each(json.data.agents, function (usersIndex, value) {
                        results.push({
                            id: value.id,
                            text: value.username,
                            type: value.type
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
                return repo.username || repo.text;
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

    // select username search
    selectUsernameSearch(placeholder) {
        $('.username_search').select2();
        let $username_search = $('#username_search');

        $username_search.select2({
            width: '100%',
            placeholder,
            allowClear: true,
            language: 'es',
            id: username_search.id || username_search.id,
            text: username_search.text || username_search.username,

            ajax: {
                type: "POST",
                url: $username_search.data('route'),
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

                    $.each(json.data.agents, function (usersIndex, value) {
                        results.push({
                            id: value.id,
                            text: value.username,
                            type: value.type
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
                return repo.username || repo.text;
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

    // Store users
    storeUsers() {
        initSelect2();
        refreshRandomPassword();
        let $form = $('#create-users-form');
        let $button = $('#create-user');

        $button.click(function () {
            $button.button('loading');

            $.ajax({
                url: $form.attr('action'),
                type: 'post',
                dataType: 'json',
                data: $form.serialize()

            }).done(function (json) {
                $('.balance').text(json.data.balance);
                $form.trigger('reset');
                $('#timezone').val('').trigger('change');
                $('#add-users-modal').modal('hide');
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

    // Status filter
    statusFilter() {
        let tree = $('#tree');
        tree.jstree({ 'core': { data: null } });

        $(document).on('click', '.status_filter', function () {
            let $route = $(this).data('route');
            let $status = $(this).data('status');
            $.get($route, function(json){
                tree.jstree(true).settings.core.data = json.data;
                tree.jstree(true).refresh();
                if ($status == '1'){
                    $('#active-status').prop('disabled', true);
                    $('#inactive-status').prop('disabled', false);
                } else {
                    $('#active-status').prop('disabled', false);
                    $('#inactive-status').prop('disabled', true);
                }

            });
        });
    }

    //Total financial
    totalFinancial(user = null) {
        let picker = initLitepickerEndToday();
        let $table = $('#total-financial-table');
        let $button = $('#update');

        if (user == null) {
            $('#financial-state-tab').on('show.bs.tab', function () {
                $table.children().remove();
                user = $('.user').val();
            });
        }

        $button.click(function () {
            $button.button('loading');
            let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
            let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');

            $.ajax({
                url: `${$table.data('route')}/${user}/${startDate}/${endDate}`,
                type: 'get',
                dataType: 'json'
            }).done(function (json) {
                $table.html(json.data.table);
            }).fail(function (json) {
                swalError(json);
            }).always(function () {
                $button.button('reset');
            });
        });
    }

    // Players table
    users() {
        $('#users-tab').on('show.bs.tab', function () {
            let $table = $('#users-table');
            let user = $('.user').val();

            if ($.fn.DataTable.isDataTable('#users-table')) {
                $table.DataTable().destroy();
            }
            $table.DataTable({
                "ajax": {
                    "url": $table.data('route') + '/' + user,
                    "dataSrc": "data.users"
                },
                "order": [],
                "columns": [
                    {"data": "username"},
                    {"data": "balance", "className": "text-right", "type": "num-fmt"}
                ],
                "initComplete": function () {
                    let api = this.api();
                    api.buttons().container()
                        .appendTo($('#table-buttons-users'));
                }
            });
        });
    }

    // Balances users report
    usersBalances() {
        let $table = $('#users-balances-table');
        let $button = $('#update');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.users"
            },
            "order": [[1, 'desc']],
            "columns": [
                {"data": "username"},
                {"data": "balance", "className": "text-right", "type": "num-fmt"}
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');
            let route = `${$table.data('route')}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });

        $table.on('xhr.dt', function (event, setting, json) {
            $('#total_balances').text(json.data.total_balances);
        });
    };

    // Update percentage
    static updatePercentage(api, route) {
        let $button = $('#update-button');
        let $form = $('#update-percentage-form');
        let $modal = $('#update-percentage');

        $modal.on('show.bs.modal', function(event) {
            let $target = $(event.relatedTarget);
            $('#agent_id').val($target.data('agent'));
            $('#percentage').val($target.data('percentage'));
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

    // Users transactions
    usersTransactions() {
        $('#users-transactions-tab').on('show.bs.tab', function () {
            let $table = $('#users-transactions-table');
            //let wallet = $('#wallet').val();
            let wallet = $('.wallet').val();

            if ($.fn.DataTable.isDataTable('#users-transactions-table')) {
                $table.DataTable().destroy();
            }

            $table.DataTable({
                "ajax": {
                    "url": $table.data('route') + '/' + wallet,
                    "dataSrc": "data.transactions"
                },
                "order": [],
                "columns": [
                    {"data": "date"},
                    {"data": "provider"},
                    {"data": "description"},
                    {"data": "debit", "className": "text-right", "type": "num-fmt"},
                    {"data": "credit", "className": "text-right", "type": "num-fmt"},
                    {"data": "balance", "className": "text-right", "type": "num-fmt"}
                ],
                "initComplete": function () {
                    let api = this.api();
                    api.buttons().container()
                        .appendTo($('#table-buttons'));
                }
            });
        });
    }

    // Table Transaction Timeline
    transactionTimeline(route,id,lengthMenu) {
        let route2 = route;
        let $button = $('#update');
        let picker = initLitepickerEndToday();
        let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
        let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');
        let dateFinal = '?start_date='+startDate+'&end_date='+endDate;
        let table = $(id).DataTable({
                processing: true,
                serverSide: true,
                lengthMenu:lengthMenu,
                ajax: {
                    url: route+dateFinal,
                    dataType: 'json',
                    type: 'get',
                },
                columns: [
                    { data: 'date' },
                    { data: 'names' },
                    { data: 'debit' },
                    { data: 'credit' },
                    { data: 'balance' },
                ],
            });

        table.on('draw.dt', function () {
            $button.button('reset');
        });

        $button.click(function () {
            $button.button('loading');
            startDate= ''
            endDate= ''
            startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
            endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');
            dateFinal = '?start_date='+startDate+'&end_date='+endDate
            table.ajax.url(route2+dateFinal).load();
            $button.button('reset');
        });

    }
}

window.Agents = Agents;
