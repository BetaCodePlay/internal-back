import {jstree} from 'jstree';
import {
    clipboard,
    swalConfirm,
    swalError,
    swalSuccessWithButton,
    swalSuccessNoButton,
    swalInput, swalInputInfo
} from "../../commons/js/core";
import {
    clearForm,
    getCookie,
    initDateRangePickerEndToday,
    initLitepickerEndToday,
    initLitepickerEndTodayNew,
    initSelect2,
    refreshRandomPassword
} from "./commons";
import moment from 'moment';
import jsPDF from 'jspdf';
import {data} from 'jquery';

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
                "responsive": true,
                "bFilter": false,
                "bInfo": false,
                "ordering": false,
                "ajax": {
                    "url": $route,
                    "dataSrc": "data.agents"
                },
                "order": [],
                "columns": [
                    {"data": "username"},
                    {"data": "type"},
                    {"data": "percentages", "type": "num-fmt"},
                    {"data": "balance", "type": "num-fmt"},
                    {"data": "actions"}
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
            "responsive": true,
            "bFilter": false,
            "bInfo": false,
            "ordering": false,
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.agents"
            },
            "order": [[1, 'desc']],
            "columns": [
                {"data": "username"},
                {"data": "balance", "type": "num-fmt"}
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
                "responsive": true,
                "bFilter": false,
                "bInfo": false,
                "ordering": false,
                "ajax": {
                    "url": $table.data('route') + '/' + user,
                    "dataSrc": "data.transactions"
                },
                "lengthMenu":[20,50,100],
                "columns": [
                    {"data": "date"},
                    {"data": "data.from"},
                    {"data": "data.to"},
                    {"data": "debit", "type": "num-fmt"},
                    {"data": "credit", "type": "num-fmt"},
                    {"data": "balance", "type": "num-fmt"}
                ],
                buttons: [
                    { extend: 'pdf', text:'PDF',className: 'pdfButton' },
                    { extend: 'copy', text:'Copy',className: 'btn btn-info u-btn-3d' },
                    { extend: 'excel', text:'Excel', className: 'btn btn-success u-btn-3d' },
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
    agentsTransactionsPaginate(lengthMenu) {
        $('#agents-transactions-tab').on('show.bs.tab', function () {

            let $tableTransaction = $('#agents-transactions-table');
            let $button = $('#updateNew');
            let picker = initLitepickerEndTodayNew();
            let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
            let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');
            let type = $('#type_select').val() === '' || $('#type_select').val() === undefined ? 'all' : $('#type_select').val();
            let transaction = $('#transaction_select').val() === '' || $('#transaction_select').val() === undefined ? 'all' : $('#transaction_select').val();
            let user = $('.user').val();

            if ($.fn.DataTable.isDataTable('#agents-transactions-table')) {
                $tableTransaction.DataTable().destroy();
            }

            let api;

            $tableTransaction.DataTable({
                responsive: true,
                bFilter: false,
                bInfo: false,
                searching: true,
                //order: [[0, 'desc']],
                ordering: true,
                processing: false,
                serverSide: false,
                lengthMenu: lengthMenu,
                ajax: {
                    url: $tableTransaction.data('route') + '/' + user + '?startDate=' + startDate + '&endDate=' + endDate + '&typeUser=' + type + '&typeTransaction=' + transaction,
                    dataType: 'json',
                    type: 'get',
                },
                columns: [
                    {"data": "date"},
                    {"data": "data.from"},
                    {"data": "data.to"},
                    {"data": "new_amount"},
                    {"data": "balance"}
                ],
                buttons: [
                    { extend: 'pdf', text:'PDF',className: 'pdfButton' },
                    { extend: 'copy', text:'Copy',className: 'btn btn-info u-btn-3d' },
                    { extend: 'excel', text:'Excel', className: 'btn btn-success u-btn-3d' },
                ],
                initComplete: function () {
                    api = this.api();
                    api.buttons().container()
                        .appendTo($('#table-buttons'));
                }
            });

            Agents.agentsTransactionsPaginateTotal($tableTransaction.data('routetotals'), user, startDate, endDate, type)

            $button.click(function () {
                $button.button('loading');
                let getStart = picker.getStartDate();
                let getEnd = picker.getEndDate();
                picker.destroy()
                picker = initLitepickerEndTodayNew(moment(getStart),moment(getEnd));
                let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
                let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');
                let type = $('#type_select').val() === '' || $('#type_select').val() === undefined ? 'all' : $('#type_select').val();
                let transaction = $('#transaction_select').val() === '' || $('#transaction_select').val() === undefined ? 'all' : $('#transaction_select').val();
                let user = $('.user').val();
                let route = `${$tableTransaction.data('route')}/${user}?startDate=${startDate}&endDate=${endDate}&typeUser=${type}&typeTransaction=${transaction}`;
                api.ajax.url(route).load();
                $tableTransaction.on('draw.dt', function () {
                    $button.button('reset');
                });
                Agents.agentsTransactionsPaginateTotal($tableTransaction.data('routetotals'), user, startDate, endDate, type)

            });

        });
    }

    // Agents Transactions Paginate Total
    static agentsTransactionsPaginateTotal(url_total, user, start_date, end_date, type) {
        $.ajax({
            url: url_total + '/' + user + '?startDate=' + start_date + '&endDate=' + end_date + '&typeUser=' + type,
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
            "responsive": true,
            "bFilter": false,
            "bInfo": false,
            "ordering": false,
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.transactions"
            },
            "order": [[0, "asc"]],
            "columns": [
                {"data": "username"},
                {"data": "debit", "type": "num-fmt"},
                {"data": "credit", "type": "num-fmt"},
                {"data": "profit", "type": "num-fmt"},
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');

            let getStart = picker.getStartDate();
            let getEnd = picker.getEndDate();
            picker.destroy()
            picker = initLitepickerEndToday(moment(getStart),moment(getEnd));

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
    agentsPayments() {
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
            "responsive": true,
            "bFilter": false,
            "bInfo": false,
            "ordering": false,
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.financial"
            },
            "order": [[1, "asc"]],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "initial_balance", "type": "num-fmt"},
                {"data": "debit", "type": "num-fmt"},
                {"data": "credit", "type": "num-fmt"},
                {"data": "final_balance", "type": "num-fmt"}
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');

            let getStart = picker.getStartDate();
            let getEnd = picker.getEndDate();
            picker.destroy()
            picker = initLitepickerEndToday(moment(getStart),moment(getEnd));

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

    changeEmailAgent() {
        $(document).on('click', '#change-email-agent', function () {
            let description = $('#description').val();
            let route = $(this).data('route');
            swalInput(route);
        });
    }

    // Change user status
    changeUserStatus() {
        $(document).on('click', '#change-user-status', function () {
            let description = $('#description').val();
            let route = $(this).data('route');
            swalInput(route);
            //swalInput(route);
        });
    }

    static getFatherRecursive(route, id, type) {
        $.ajax({
            url: route,
            type: 'get',
            data: {
                id, type
            }
        }).done(function (response) {
            $('.agentsSet').text(response.data.cant_agents);
            $('.playersSet').text(response.data.cant_players);
            $('.appendTreeFather').html('');
            let initUl = '';
            let finishUl = '';
            $.each(response.data.fathers, function (index, val) {
                initUl = initUl + '<ul><li><strong>' + val.username + '</strong>'
                finishUl = finishUl + '</li></ul>'
            });
            $('.appendTreeFather').append(initUl + finishUl);
        });
    }

    // Dashboard
    dashboard() {
        initSelect2();
        clipboard();
        let $tree = $('#tree');
        $tree.jstree({
            'core': {
                'data': $('#tree').data('json')
            }
        });

        $tree.on('changed.jstree', function (event, data) {
            if (data.action == "ready" || data.action == "select_node") {

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
                        //TODO Init Set Modal
                        $('.userIdSet').text(json.data.user.id);
                        $('.agentIdSet').text(json.data.user.agent);
                        $('.userSet').text(json.data.user.username);
                        $('.emailSet').text(json.data.user.email);
                        $('.fatherSet').text(json.data.father);
                        $('.typeSet').text(json.data.user.typeSet);
                        $('.createdSet').text(json.data.user.created);
                        $('.cantA_P').show();
                        $('.cantA_P').show();
                        if (json.data.type != "agent") {
                            $('.cantA_P').hide();
                            $('.cantA_P').hide();
                        }
                        // $('.agentsSet').text(json.data.cant_agents);
                        // $('.playersSet').text(json.data.cant_players);
                        // let initUl = '';
                        // let finishUl = '';
                        // $.each(json.data.fathers,function(index,val) {
                        //     initUl = initUl + '<ul style="margin-left: -13%!important;"><li><strong>'+val.username+'</strong>'
                        //     finishUl = finishUl + '</li></ul>'
                        // });
                        // $('.appendTreeFather').append(initUl+finishUl);

                        setTimeout(function () {
                            Agents.getFatherRecursive($('#details-user-get').data('route'), id, type);
                        }, 500)
                        //TODO Finish Set Modal
                        alert('Orlando 3')
                        console.log(json.data);
                        $('#username').text(json.data.user.username);
                        $('#agent_timezone').text(json.data.user.timezone);
                        $('.balance').text(json.data.balance);
                        $('.balance-bonus').text(json.data.balance-bonus);
                        $('.balanceAuth_' + json.data.user.id).text('');
                        $('.balanceAuth_' + json.data.user.id).text(json.data.balance);
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
                            $('#bonus-show').addClass('d-none');
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
                            $('#bonus-show').removeClass('d-none');
                        }

                        if (json.data.myself) {
                            if (!json.data.agent_player) {
                                $('#new-user, #new-agent').addClass('d-none');
                            } else {
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
            }

        })

    }

    //TODO New user tree structure PRO
    treePro(urlTree) {
        let listUsers;
        let listMakers;
        let idCurrentUser = $('#tree-pro-master').data('idtreepro');

        $.ajax({
            url: urlTree,
            type: 'get',
            dataType: 'json',
        }).done(function (data) {
            if (data.status === 'OK') {
                listUsers = data.data.tree;
                listMakers = data.data.makers;
                scanSearch(idCurrentUser);
                drawMakers();
                $('#tree-pro-master').find('a.jstree-anchor').click();
            } else {
                console.log('Error al consultar usuarios',data)
            }
        }).fail(function () {
            Swal.fire(
                'Ha ocurrido un error inesperado',
                'Recarga o intenta de nuevo mas tarde.',
                'error'
            )
        }).always(function () {

        });

        function scanSearch(id) {
            let users = listUsers.filter(user => user.owner_id === id);
            let usersTemp;
            let userHtmlTempMini = '';
            let usersHtmlTemp;
            let last = '';
            let type_user;
            let atmIcon;
            let atmType;


            $.each(users, function (index, value) {
                usersTemp = listUsers.filter(user => user.owner_id === value.id);

                if (index + 1 === users.length) {
                    last = 'jstree-last';
                }

                switch(value.type_user) {
                    case 1:
                        type_user = 'agent';
                        atmIcon = 'fa-star';
                        atmType = 'agent';
                        break;
                    case 2:
                        type_user = 'agent';
                        atmIcon = 'fa-users';
                        atmType = 'agent';
                        break;
                    case 5:
                        type_user = 'user';
                        atmIcon = 'fa-user';
                        atmType = 'user';
                        break;
                    default:
                        type_user = 'user-null';
                        atmIcon = 'icon-null';
                        atmType = 'null'
                }

                if (usersTemp.length > 0) {
                    userHtmlTempMini = userHtmlTempMini + '<li class="jstree-node init_agent jstree-closed ' + last + '"><i class="jstree-icon jstree-ocl jstree-more" data-idtreepro="' + value.id + '" data-typetreepro="' + type_user + '" role="' + value.owner_id + '"></i><a class="jstree-anchor" href="javascript:void(0)"><i class="jstree-icon jstree-themeicon fa '+ atmIcon +' jstree-themeicon-custom" role="presentation"></i>' + value.username + '</a></li>';
                } else {
                    userHtmlTempMini = userHtmlTempMini + '<li class="jstree-node init_'+ atmType +' jstree-leaf ' + last + '"><i class="jstree-icon jstree-ocl" data-idtreepro="' + value.id + '" data-typetreepro="' + type_user + '" role="' + value.owner_id + '"></i><a class="jstree-anchor" href="javascript:void(0)"><i class="jstree-icon jstree-themeicon fa '+ atmIcon +' jstree-themeicon-custom" role="presentation"></i>' + value.username + '</a></li>';
                }
            })

            usersHtmlTemp = '<ul role="group" class="jstree-children">' + userHtmlTempMini + '</ul>';

            $('[data-idtreepro="' + id + '"]').parent().append(usersHtmlTemp);
        }

        //TODO Draw marker for blocking
        function drawMakers() {
            let makers = listMakers;

            $('#maker option[value!=""]').remove();
            if(makers.length > 0){
                $.each(makers, function (index, val) {
                    $('#maker').append("<option value=" + val.maker + ">" + val.maker + "</option>");

                });
            }
        }

        $(document).on('click', '.jstree-more', function () {
            let $this = $(this);
            let $obj = $this.parent();

            if ($obj.hasClass('jstree-open')) {
                $obj.removeClass('jstree-open');
                $obj.addClass('jstree-closed');
                $obj.find('.jstree-children').remove();
            } else {
                $obj.removeClass('jstree-closed');
                $obj.addClass('jstree-open');
                scanSearch($this.data('idtreepro'));
            }
        });

        $(document).on('click', 'a.jstree-anchor', function (){
            let $this = $(this);
            let type = $this.parent().find('.jstree-icon.jstree-ocl').eq(0).data('typetreepro');
            let id = $this.parent().find('.jstree-icon.jstree-ocl').eq(0).data('idtreepro');
            let $container = $('#tree-pro');

            $('a.jstree-anchor').removeClass('jstree-clicked');
            $this.addClass('jstree-clicked');

            console.log('this is a route', $container.data('route'));

            $.ajax({
                url: $container.data('route'),
                type: 'get',
                dataType: 'json',
                data: {
                    id, type
                }

            }).done(function (json) {
                //TODO Init Set Modal
                $('.userIdSet').text(json.data.user.id);
                $('.agentIdSet').text(json.data.user.agent);
                $('.userSet').text(json.data.user.username);
                $('.emailSet').text(json.data.user.email);
                $('.fatherSet').text(json.data.father);
                $('.typeSet').text(json.data.user.typeSet);
                $('.createdSet').text(json.data.user.created);
                $('.cantA_P').show();
                $('.cantA_P').show();
                if (json.data.type != "agent") {
                    $('.cantA_P').hide();
                    $('.cantA_P').hide();
                }

                setTimeout(function () {
                    Agents.getFatherRecursive($('#details-user-get').data('route'), id, type);
                }, 500)
                //TODO Finish Set Modal

                //TODO Init Set Modal Bonus
                $('#info-bonus-description').html(json.data.campaignDescription);
                //TODO Finish Set Modal Bonus
                console.log('orlando 4');
                $('#username').text(json.data.user.username);
                $('#agent_timezone').text(json.data.user.timezone);
                $('.balance').text(json.data.balance);
                $('.balance_bonus').text(json.data.balance_bonus);
                $('.balanceAuth_' + json.data.user.id).text('');
                $('.balanceAuth_' + json.data.user.id).text(json.data.balance);
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
                    $('#bonus-show').addClass('d-none');
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
                    $('#bonus-show').removeClass('d-none');
                }

                if (json.data.myself) {
                    if (!json.data.agent_player) {
                        $('#new-user, #new-agent').addClass('d-none');
                    } else {
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
                if(json.data.user.type_user !== 5) {

                }

            }).fail(function (json) {
                swalError(json);
            });
        })

        $('#tree-pro-init').find('.jstree-anchor').eq(0).click();
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
                {"data": "amount", "type": "num-fmt"},
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

    // Financial state Makers
    financialStateMakers() {
         initSelect2();
         initDateRangePickerEndToday(open = 'right');
        //  let picker = initLitepickerEndToday();
         let $table = $('#financial-state-table-makers');
        //  let currency_iso = $('#currency_id').val() === ''?'':$('#currency_id').val();
        //  let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
        //  let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');
         let $button = $('#update');
         $button.trigger('click');
         let api;

         $button.click(function () {
             $button.button('loading');
                let startDate = $('#start_date').val();
                let endDate = $('#end_date').val();
                let currency_iso = $('#currency').val() === '' ? '' : $('#currency').val();

            $.ajax({
                url: `${$table.data('route')}/${startDate}/${endDate}/${currency_iso}`,
                type: 'get',
                dataType: 'json'

                }).done(function (json) {
                    $table.html(json.data.table);

                }).fail(function (json) {
                    swalError(json);

            }).always(function () {
                $button.button('reset');
            });

            Agents.financialStateMakersTotal($table.data('routetotals'), startDate, endDate, currency_iso);
        });
    }

    // Agents Transactions Paginate Total
    static financialStateMakersTotal(url_total, start_date, end_date, currency_iso, provider_id, whitelabel_id) {
        $.ajax({
            url: url_total + '?startDate=' + start_date + '&endDate=' + end_date + '&currency_iso=' + currency_iso+ '&provider_id=' + provider_id+ '&whitelabel_id=' + whitelabel_id,
            type: 'get',
        }).done(function (response) {
            $('.financialStateDataMakersTotals').empty();
            $('.financialStateDataMakersTotals').append(response)
        });
    }

    // Financial state Makers
    financialStateMakersDetails() {
        initSelect2();
        initDateRangePickerEndToday(open = 'right');
        let api;
        let $table = $('#financial-state-table');
        let $button = $('#update');
        $button.trigger('click');
        $button.click(function () {
            $button.button('loading');
            let whitelabel_id = $('#whitelabel').val() === '' ? '' : $('#whitelabel').val();
            let provider_id = $('#provider').val() === '' ? '' : $('#provider').val();
            let currency_iso = $('#currency').val() === '' ? '' : $('#currency').val();
            //  let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
            //  let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');
            let startDate = $('#start_date').val();
            let endDate = $('#end_date').val();
            $.ajax({
                url: `${$table.data('route')}/${startDate}/${endDate}?currency_iso=${currency_iso}&provider_id=${provider_id}&whitelabel_id=${whitelabel_id}`,
                type: 'get',
                dataType: 'json'

            }).done(function (json) {
                $table.html(json.data.table);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
            Agents.financialStateMakersTotal($table.data('routetotals'), startDate, endDate, currency_iso, provider_id, whitelabel_id);
        });
    }

    printDocumentMakers() {
        let $button = $('#print-pdf-d');

        $button.click(function () {
            $button.button('loading');

            var doc = new jsPDF('p', 'pt', 'letter');

            var margin = 10;
            var scale = (doc.internal.pageSize.width - margin * 2) / document.body.scrollWidth;
            doc.html(document.getElementById('print-document'), {
                x: margin,
                y: margin,
                html2canvas: {
                    scale: scale,
                },
                callback: function (doc) {
                    // Comentado para pruebas
                    // doc.output('dataurlnewwindow', {filename: 'examen.pdf'});
                    doc.save('makers' + Date.now() + '.pdf');
                    $button.button('reset');
                }
            });
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

            let getStart = picker.getStartDate();
            let getEnd = picker.getEndDate();
            picker.destroy()
            picker = initLitepickerEndToday(moment(getStart),moment(getEnd));


            let username_like = $('#username_like').val() === '' ? '' : '&username_like=' + $('#username_like').val();
            let provider_id = $('#provider_id').val() === undefined || $('#provider_id').val() === '' ? '' : '&provider_id=' + $('#provider_id').val();
            let _hour = $('#_hour').val() === '' ? '' : '&_hour=' + $('#_hour').val();
            let test = '?test=false'
            let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
            let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');

            $.ajax({
                url: `${$table.data('route')}/${user}/${startDate}/${endDate}${test}${username_like}${provider_id}${_hour}`,
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

    // Financial state New
    financialStateNew(user = null, lengthMenuItems) {

        let picker = initLitepickerEndToday();
        let $tableTransaction = $('#financial-statetable');
        let $button = $('#update');

        let username_like = $('#username_like').val() === '' ? '' : '&username_like=' + $('#username_like').val();
        let provider_id = $('#provider_id').val() === undefined || $('#provider_id').val() === '' ? '' : '&provider_id=' + $('#provider_id').val();
        let _hour = $('#_hour').val() === '' ? '' : '&_hour=' + $('#_hour').val();
        let test = '?test=false'

        let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
        let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');

        let api;

        $tableTransaction.DataTable({
            responsive: true,
            bFilter: false,
            bInfo: false,
            searching: true,
            order: [[0, 'asc']],
            ordering: true,
            processing: false,
            serverSide: false,
            lengthMenu: lengthMenuItems,
            ajax: {
                url: $tableTransaction.data('route') + '/' + user + '/' + startDate + '/' + endDate + test + username_like + provider_id + _hour,
                dataType: 'json',
                type: 'get',
            },
            columns: [
                {"data": "name"},
                {"data": "played"},
                {"data": "won"},
                {"data": "bet"},
                {"data": "profit"},
                {"data": "rpt"}
            ],
            buttons: [
                { extend: 'pdf', text:'PDF',className: 'pdfButton' },
                { extend: 'copy', text:'Copy',className: 'btn btn-info u-btn-3d' },
                { extend: 'excel', text:'Excel', className: 'btn btn-success u-btn-3d' },
            ],
            initComplete: function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');

            let getStart = picker.getStartDate();
            let getEnd = picker.getEndDate();
            picker.destroy()
            picker = initLitepickerEndToday(moment(getStart),moment(getEnd));

            let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
            let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');
            let user = $('.user').val();
            let route = $tableTransaction.data('route') + '/' + user + '/' + startDate + '/' + endDate;
            api.ajax.url(route).load();
            $tableTransaction.on('draw.dt', function () {
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

            let getStart = picker.getStartDate();
            let getEnd = picker.getEndDate();
            picker.destroy()
            picker = initLitepickerEndToday(moment(getStart),moment(getEnd));

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
                data: $form_agent.serialize() + '&type=' + type + '&lock_users=' + lock_users
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
                data: $form_agent.serialize() + '&type=' + type + '&lock_users=' + lock_users
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
                data: $form_user.serialize() + '&type=' + type + '&lock_users=' + lock_users
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
                data: $form_user.serialize() + '&type=' + type + '&lock_users=' + lock_users
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

        $form_agent.keypress(function (event) {
            if (event.keyCode === 13) {
                event.preventDefault();
            }
        });

        $form_user.keypress(function (event) {
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

    // Balance Current of Agent
    balanceAgentCurrent($url) {
        $('.balance').text('');
        $.ajax({
            url: $url,
            method: 'get',
            dataType: 'json'
        }).done(function (json) {
            if (json.status) {
                $('.balance').text(json.balance);
            }

        }).fail(function (json) {
            swalError(json);
        });
    }
    // Move agent user
    moveAgentUser() {
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

        $form.keypress(function (event) {
            if (event.keyCode === 13) {
                event.preventDefault();
            }
        });
    }

    // Move agent
    moveAgent() {
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

        $form.keypress(function (event) {
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
                $('.balance_bonus').text(json.data.balanceBonus);
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
                {"data": "debit", "type": "num-fmt"},
                {"data": "credit", "type": "num-fmt"},
                {"data": "balance", "type": "num-fmt"}
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
        $('[data-target]').click(function () {
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
                $('.balance_bonus').text(json.data.balanceBonus);
                let userInput = json.data.balance_auth;
                alert('Orlando 1');
                let userclass = 'balanceAuth_'+json.data.auth_balance;
                let userclass2 = '.balanceAuth_'+json.data.auth_balance;
                document.getElementsByClassName(userclass).innerHTML = userInput;

                $(userclass2).text(userInput);
                console.log(userInput,userclass,userclass2)

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
                alert('Orlando 2');
                $('.balance').text(json.data.balance);
                $('.balance_bonus').text(json.data.balanceBonus);
                let userInput = json.data.balance_auth;
                let userclass = 'balanceAuth_'+json.data.auth_balance;
                let userclass2 = '.balanceAuth_'+json.data.auth_balance;
                document.getElementsByClassName(userclass).innerHTML = userInput;

                $(userclass2).text(userInput);
                console.log(userInput,userclass,userclass2)

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

        $form.keypress(function (event) {
            if (event.keyCode === 13) {
                event.preventDefault();
            }
        });
    }

    //provider currency
    providerCurrency() {
        let $currency = $('#currency');
        $.get('provider-currency/' + $currency.val(), function (data) {
            $.each(data.data, function (key, element) {
                $('#provider').append("<option value=" + element.id + ">" + element.name + "</option>");
            });
        });
    }

    //provider lcok data
    providerLockData() {
        initSelect2();
        let $table = $('#locked-providers-table');
        let $button = $('#update');
        let api;

        $table.DataTable({
            "responsive": true,
            "bFilter": false,
            "bInfo": false,
            "ordering": false,
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.agents"
            },
            "order": [[0, 'asc']],
            "columns": [
                {"data": "agent"},
                {"data": "provider"},
                {"data": "date"},
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
            $.get(route, function (json) {
                $('#relocation-agents option[value!=""]').remove();
                $.each(json.data.agents, function (key, element) {
                    agents.append("<option value=" + element.id + ">" + element.username + "</option>");
                });
            });
        });
    }

    // Get reset email
    resetEmail() {

        if (document.getElementById( "reset-email" ) && document.getElementById( "reset-email-form" )) {

            let $button = $('#reset-email');
            let $form = $('#reset-email-form');

            $button.click(function () {
                $button.button('loading');

                $.ajax({
                    url: $form.attr('action'),
                    method: 'post',
                    data: $form.serialize()

                }).done(function (json) {
                    $('#reset-email-modal').modal('hide');
                    swalSuccessWithButton(json);
                    $form.trigger('reset');

                }).fail(function (json) {
                    swalError(json);

                }).always(function () {
                    $button.button('reset');
                });
            });

        }

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
    searchAgent(placeholder) {
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
        $('.agent_id_search').change('select2:selecting', function (e) {
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
                setTimeout(function () {
                    Agents.getFatherRecursive($('#details-user-get').data('route'), json.data.user.id, json.data.type);
                }, 500)
                //TODO MODAL

                $('.userIdSet').text(json.data.user.id);
                $('.agentIdSet').text(json.data.user.agent);
                $('.userSet').text(json.data.user.username);
                $('.emailSet').text(json.data.user.email);
                $('.fatherSet').text(json.data.father);
                $('.typeSet').text(json.data.user.typeSet);
                $('.createdSet').text(json.data.user.created);
                $('.cantA_P').show();
                $('.cantA_P').show();
                if (json.data.type != "agent") {
                    $('.cantA_P').hide();
                    $('.cantA_P').hide();
                }


                $('#username').text(json.data.user.username);
                $('#agent_timezone').text(json.data.user.timezone);
                $('.balance').text(json.data.balance);
                $('#user_type').html(json.data.user.type);
                $('#status').html(json.data.user.status);
                $('#wallet').val(json.data.wallet);
                $('.wallet').val(json.data.wallet);
                $('.user').val(json.data.user.id);
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
                    $('#bonus-show').addClass('d-none');
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
                    $('#bonus-show').removeClass('d-none');
                }

                if (json.data.myself) {
                    if (!json.data.agent_player) {
                        $('#new-user, #new-agent').addClass('d-none');
                    } else {
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

    //Select maker
    selectCategoryMaker() {
        // initSelect2();
        $('#maker').on('change', function () {
            let maker = $(this).val();
            let categories = $('#category');
            let route = $(this).data('route');
            if (maker !== '') {
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
                }).fail(function (json) {
                });
            }
        }).trigger('change');
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
            id: $username_search.id || $username_search.id,
            text: $username_search.text || $username_search.username,

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

    // select user search
    selectUserSearch(placeholder,title1,title2) {
        $('.username_search').select2();
        let $username_search = $('#username_search');

        $username_search.select2({
            width: '100%',
            placeholder,
            allowClear: true,
            language: 'es',
            id: $username_search.id || $username_search.id,
            text: $username_search.text || $username_search.username,

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
                            roles: value.roles
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

        $username_search.on('select2:select', function (e) {
            var data = e.params.data;
            $('#listRoles').html('');
            $('.textTitleRol').text('');
            let optionTmp = '';
            $('.textTitleRol').text(title2);
            if(data.roles.length > 0){
                $('.textTitleRol').text(title1);
                $.each(data.roles, function (usersIndex, value) {
                    optionTmp += '<div class="modal-header deleteHtml_'+value.id+'" style="background-color: #20c997" >\n' +
                        '  <strong>'+value.description+'</strong>\n' +
                        '     <button type="button" class="close removeRoleUser" data-user="'+data.id+'" data-rol="'+value.id+'" data-dismiss="modal" aria-label="Close">\n' +
                        '    <span aria-hidden="true" style="color: red!important;font-size: larger!important;">&times;</span>\n' +
                        '  </button>\n' +
                        '  </div>'
                });
            }

            $('#listRoles').append(optionTmp);
        });

        $(document).on('click', '.removeRoleUser', function () {
            $("#rol_id").val("").trigger( "change" );
            $("#username_search").val("").trigger( "change" );

            let $route = $('#listRoles').data('route_delete')+'?user_id='+$(this).data('user')+'&rol_id='+$(this).data('rol');
            let $rol = $(this).data('rol');
            swalConfirm($route, function () {
                $('#listRoles').html('');
                $('.textTitleRol').text('');
                $('.deleteHtml_'+$rol).remove();
            });
        });

    }

    selectWhitelabelMakers() {
        initSelect2();
        $('#whitelabel').on('change', function () {
            let whitelabel = $(this).val();
            let route = $(this).data('route');
            let provider = $('#provider');
            if (whitelabel !== '') {
                $.ajax({
                    url: route,
                    type: 'get',
                    dataType: 'json',
                    data: {
                        whitelabel
                    }
                }).done(function (json) {
                    $('#provider option[value!=""]').remove();
                    $(json.data.providers).each(function (key, element) {
                        provider.append("<option value=" + element.id + ">" + element.name + "</option>");
                    })
                    provider.prop('disabled', false);
                }).fail(function (json) {

                });
            } else {
                provider.val('');
            }
        }).trigger('change');
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
        tree.jstree({'core': {data: null}});

        $(document).on('click', '.status_filter', function () {
            let $route = $(this).data('route');
            let $status = $(this).data('status');
            $.get($route, function (json) {
                tree.jstree(true).settings.core.data = json.data;
                tree.jstree(true).refresh();
                if ($status == '1') {
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

            let getStart = picker.getStartDate();
            let getEnd = picker.getEndDate();
            picker.destroy()
            picker = initLitepickerEndToday(moment(getStart),moment(getEnd));

            let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
            let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');
            let _hour = $('#_hour').val() === '' ? '' : '?_hour=' + $('#_hour').val();

            $.ajax({
                url: `${$table.data('route')}/${user}/${startDate}/${endDate}${_hour}`,
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
                "responsive": true,
                "bFilter": false,
                "bInfo": false,
                "ordering": false,
                "ajax": {
                    "url": $table.data('route') + '/' + user,
                    "dataSrc": "data.users"
                },
                "order": [],
                "columns": [
                    {"data": "username"},
                    {"data": "balance", "type": "num-fmt"}
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
            "responsive": true,
            "bFilter": false,
            "bInfo": false,
            "ordering": false,
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.users"
            },
            "order": [[1, 'desc']],
            "columns": [
                {"data": "username"},
                {"data": "balance", "type": "num-fmt"}
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

        $modal.on('show.bs.modal', function (event) {
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
    usersTransactions(lengthMenu) {
        $('#users-transactions-tab').on('show.bs.tab', function () {
            let $table = $('#users-transactions-table');
            //let wallet = $('#wallet').val();
            let wallet = $('.wallet').val();

            if ($.fn.DataTable.isDataTable('#users-transactions-table')) {
                $table.DataTable().destroy();
            }

            $table.DataTable({
                "responsive": true,
                "bFilter": true,
                "bInfo": false,
                "ordering": true,
                "lengthMenu": lengthMenu,
                "ajax": {
                    "url": $table.data('route') + '/' + wallet,
                    "dataSrc": "data.transactions"
                },
                "order": [],
                "columns": [
                    {"data": "date"},
                    {"data": "provider"},
                    {"data": "description"},
                    {"data": "debit", "type": "num-fmt"},
                    {"data": "credit", "type": "num-fmt"},
                    {"data": "balance", "type": "num-fmt"}
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
    transactionTimeline(lengthMenu) {

        let $tableTransaction = $('#tableTimeline');
        let $button = $('#update');
        let picker = initLitepickerEndToday();
        let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
        let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');
        let dateFinal = '?start_date=' + startDate + '&end_date=' + endDate;

        let api;

        $tableTransaction.DataTable({
             responsive: true,
             bFilter: false,
             bInfo: false,
             searching: true,
             order: [[0, 'asc']],
             ordering: true,
             processing: false,
             serverSide: false,
             lengthMenu: lengthMenu,
            ajax: {
                url: $tableTransaction.data('route') + dateFinal,
                dataType: 'json',
                type: 'get',
            },
            columns: [
                {data: 'date'},
                {data: 'names'},
                {data: 'debit'},
                {data: 'credit'},
                {data: 'balance'},
                {data: 'balanceFrom'}
            ],
             buttons: [
                 { extend: 'pdf', text:'PDF',className: 'pdfButton' },
                 { extend: 'copy', text:'Copy',className: 'btn btn-info u-btn-3d' },
                 { extend: 'excel', text:'Excel', className: 'btn btn-success u-btn-3d' },
                 // { extend: 'pdfHtml5', text:'PDF-5',className: 'pdfButton' },
                 // { extend: 'pdfHtml5',
                 //     text: 'Save current page',
                 //     download: 'open',
                 //     exportOptions: {
                 //         modifier: {
                 //             page: 'current'
                 //         }
                 //     }
                 // }
             ],
             initComplete: function () {
                 api = this.api();
                 api.buttons().container()
                     .appendTo($('#table-buttons'));
             }
        });

        $button.click(function () {
            $button.button('loading');

            let getStart = picker.getStartDate();
            let getEnd = picker.getEndDate();
            picker.destroy()
            picker = initLitepickerEndToday(moment(getStart),moment(getEnd));

            let startDate = moment(picker.getStartDate()).format('YYYY-MM-DD');
            let endDate = moment(picker.getEndDate()).format('YYYY-MM-DD');
            let dateFinal = '?start_date=' + startDate + '&end_date=' + endDate
            let route = $tableTransaction.data('route') + dateFinal;
            api.ajax.url(route).load();
            $tableTransaction.on('draw.dt', function () {
                $button.button('reset');
            });
        });

    }

    //Exclude Provider
    excludeProviderUserList() {
        initSelect2();
        initDateRangePickerEndToday(open = 'right');
        let $table = $('#exclude-providers-agents-table');
        let $button = $('#update');
        let api;
        let $form = $('#exclude-provider-agents-form');
        let $buttonUpdate = $('#save');
        clearForm($form);

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.agents"
            },
            "order": [
                [0, "asc"]
            ],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "category"},
                {"data": "makers"},
                {"data": "currency_iso"},
                {"data": "date", "className": "text-right"},
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
            let category = $('#category_filter').val();
            let maker = $('#maker_filter').val();
            let currency = $('#currency_filter').val();
            let startDate = $('#start_date').val();
            let endDate = $('#end_date').val();
            let route = `${$table.data('route')}/${startDate}/${endDate}?category=${category}&maker=${maker}&currency=${currency}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });

        $buttonUpdate.click(function () {
            $buttonUpdate.button('loading');
            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                dataType: 'json',
                data: $form.serialize()

            }).done(function (json) {
                $button.button('loading');
                $form.trigger('reset');
                let route = `${$table.data('route')}`;
                api.ajax.url(route).load();
                $table.on('draw.dt', function () {
                    $button.button('reset');
                });
                swalSuccessWithButton(json);
            }).fail(function (json) {
                swalError(json);
            }).always(function () {
                $buttonUpdate.button('reset');
            });
        });
    }

    detailsUserModal() {
        $('#details-user-modal').on('show.bs.modal', function (e) {
            //console.log('mostrar')
        })
        // $('#details-user-modal').on('hidden.bs.modal', function (e) {
        //     console.log('cerrar')
        // })
    }

}

window.Agents = Agents;
