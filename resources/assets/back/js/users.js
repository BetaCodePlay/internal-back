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

class Users {

    // Activate temp
    static activateTemp(api, route) {
        let $button = $('#send-activation');
        let $form = $('#active-form');
        let $modal = $('#activation-modal');

        $modal.on('show.bs.modal', function (event) {
            let $target = $(event.relatedTarget);
            $('#email_user').val($target.data('email'));
            $('#user_name').val($target.data('username'));

            $button.click(function () {
                $button.button('loading');

                $.ajax({
                    url: $form.attr('action'),
                    method: 'post',
                    data: $form.serialize()

                }).done(function (json) {
                    $('#activation-modal').modal('hide');
                    swalSuccessWithButton(json);
                    api.ajax.url(route).load();

                }).fail(function (json) {
                    swalError(json);

                }).always(function () {
                    $button.button('reset');
                });
            });
        })
    }

    // Advanced search
    advancedSearch() {
        initSelect2();
        let api;
        let $table = $('#users-table');
        let $button = $('#search');
        let $form = $('#advanced-search-form');
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
                {"data": "email"},
                {"data": "first_name"},
                {"data": "last_name"},
                {"data": "gender"},
                {"data": "status", "className": "text-right"}
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

    // Bonus transactions
    bonusTransactions() {
        let $button = $('#bonus-transactions');
        let $form = $('#bonus-transactions-form');
        $button.click(function () {
            $button.button('loading');

            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                data: $form.serialize()

            }).done(function (json) {
                $form.trigger('reset');
                $('#main-balance').text(json.data.balance);
                $('#bonus-transaction-modal').modal('hide');
                swalSuccessWithButton(json);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }

    // Change status
    changeStatus() {
        let $button = $('.change-status');

        $button.click(function () {
            let $target = $(this);
            let description = $('#description').val();
            $target.button('loading');
            let route = `${$target.data('route')}`;
            swalInput(route, function () {
                $target.button('reset');
            });
        });
    }

    // Disable account
    disableAccount() {
        let $button = $('[data-payment-method-account]');

        $button.click(function () {
            let $target = $(this);
            let route = `${$target.data('route')}`;
            swalConfirm(route, function () {
                setTimeout(() => {
                    window.location.href = '';
                }, 1000);
            });
        });
    }

    // Details
    details() {
        initSelect2();
    }

    // Details modals
    detailsModals() {
        $('.open-modal').click(function () {
            let modal = $(this).data('href');
            $(modal).modal('show');
        });
    }

    // Users temp
   documents() {
        let $table = $('#documents-table');
        let $modal = $('#watch-document-modal');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.documents"
            },
            "order": [],
            "columns": [
                {"data": "user"},
                {"data": "user_name"},
                {"data": "currency"},
                {"data": "name_type"},
                {"data": "date", "className": "text-right"},
                {"data": "action", "className": "text-right"}
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
                Users.documentsApproved(api, $table.data('route'));
                Users.documentsEdit(api, $table.data('route'));
                Users.documentsRejected(api, $table.data('route'))
            }
        });

       $modal.on('show.bs.modal', function (event) {
           let $target = $(event.relatedTarget);
           $('#document').append($target.data('document'));
       })

       $modal.on('hidden.bs.modal', function () {
           $modal.find('#document').html('');
       })

       $table.on('xhr.dt', function (event, settings, json, xhr) {
           if (xhr.status === 500 || xhr.status === 422) {
               swalError(xhr);
               $button.button('reset');
           }
       });
   };

    // Document approved
    static documentsApproved(api, route) {
        let $button = $('#approved');
        let $form = $('#approved-document-form');
        let $modal = $('#document-approved-modal');

        $button.click(function () {
            $button.button('loading');
            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                dataType: 'json',
                data: $form.serialize()

            }).done(function (json) {
                $form.trigger('reset');
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
            $('#type').val($target.data('type'));
            $('#user').val($target.data('user'));
            $('#status').val($target.data('status'));
            $('#document_id').val($target.data('id'));
        })
    }

    // Document edit
    static documentsEdit(api, route) {
        initFileInput();

        let $button = $('#edit');
        let $form = $('#edit-document-form');
        let $modal = $('#document-edit-modal');

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
            $('#user_id_edit').val($target.data('user'));
            $('#document_id_edit').val($target.data('id'));
        })
    }

    // Documents by user
    documentsByUser() {
        let $table = $('#verification-document-table');
        let $button = $('#verification-document-update');
        let user = $('#user_id').val();
        let $modal = $('#watch-document-modal');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.documents"
            },
            "order": [],
            "columns": [
                {"data": "date"},
                {"data": "name_type"},
                {"data": "status", "className": "text-right"},
                {"data": "visualize", "className": "text-right"}
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#verification-document-table-buttons'));
                Users.documentsRejected(api, $table.data('route'));
            }
        });

        $modal.on('show.bs.modal', function (event) {
            let $target = $(event.relatedTarget);
            $('#document').append($target.data('document'));
        })

        $modal.on('hidden.bs.modal', function () {
            $modal.find('#document').html('');
        })

        $button.click(function () {
            $button.button('loading');
            user = $('#user_id').val();
            let route = `${$table.data('route')}/${user}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    }

    // Document rejected
    static documentsRejected(api, route) {
        let $button = $('#rejected');
        let $form = $('#rejected-document-form');
        let $modal = $('#document-rejected-modal');

        $button.click(function () {
            $button.button('loading');
            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                dataType: 'json',
                data: $form.serialize()

            }).done(function (json) {
                $form.trigger('reset');
                $modal.modal('hide');
                api.ajax.url(route).load();
                swalSuccessWithButton(json);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });

        $modal.on('show.bs.modal', function (event) {
            let $target = $(event.relatedTarget);
            $('#type_id').val($target.data('type'));
            $('#user_id').val($target.data('user'));
            $('#status_id').val($target.data('status'));
            $('#id_document').val($target.data('id'));
            $('#file_document').val($target.data('file'));
        })
    }

    // Exclude provider user list
    excludeProviderUserList() {
        initSelect2();
        let $table = $('#exclude-providers-users-table');
        let $button = $('#update-exclude');
        let api;
        let $form = $('#exclude-provider-user-form');
        let $buttonUpdate = $('#save');
        clearForm($form);

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.users"
            },
            "order": [
                [0, "asc"]
            ],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "name"},
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
            let route = `${$table.data('route')}`;
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
    };

    //Select maker
    selectProvidersMaker(){
        initSelect2();
        $('#provider').on('change', function () {
            let provider = $(this).val();
            let route = $(this).data('route');
            let makers = $('#maker');
            if (provider !== '') {
                $.ajax({
                    url: route,
                    type: 'get',
                    dataType: 'json',
                    data: {
                        provider
                    }
                }).done(function (json) {
                    $('#maker option[value!=""]').remove();
                    $(json.data.makers).each(function (key, element) {
                        makers.append("<option value=" + element.maker + ">" + element.maker + "</option>");
                    })
                    makers.prop('disabled', false);
                }).fail(function (json) {

                });
            } else {
                makers.val('');
            }
        }).trigger('change');
    }

    // Login user
    loginUser() {
        let user = $('#user_id').val();
        let $button = $('#login_user');
        $button.click(function () {
            $.ajax({
                url: $button.data('route'),
                method: 'post',
                data: {user: user}
            });
        });
    };

    // Manual adjustments
    manualAdjustments() {
        let $button = $('#manual-adjustments');
        let $form = $('#manual-adjustments-form');

        $button.click(function () {
            $button.button('loading');

            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                data: $form.serialize()

            }).done(function (json) {
                $form.trigger('reset');
                $('form select').val(null).trigger('change');
                $('#main-balance').text(json.data.balance);
                $('#manual-adjustments-modal').modal('hide');
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
        let $button = $('#manual-transactions');
        let $form = $('#manual-transactions-form');
        let $modal = $('#manual-transaction-modal');

        $modal.on('show.bs.modal', function (event) {
            let $target = $(event.relatedTarget);
            let transactionType = $target.data('transaction-type')
            let name = $target.data('transaction-name');
            $('#manual-transaction-type').text(name);
            $('#transaction_type').val(transactionType);
        });

        $button.click(function () {
            $button.button('loading');
            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                data: $form.serialize()
            }).done(function (json) {
                $form.trigger('reset');
                $('form select').val(null).trigger('change');
                $('#main-balance').text(json.data.balance);
                $('#manual-transaction-modal').modal('hide');
                swalSuccessWithButton(json);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }

    // Payments transactions
    paymentsTransactions() {
        let $table = $('#payments-transactions-table');
        let user = $('#user_id').val();
        let currency = $('#currency').val();
        let $button = $('#update-payments');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.transactions"
            },
            "order": [[1, "desc"]],
            "columns": [
                {"data": "date"},
                {"data": "id"},
                {"data": "provider"},
                {"data": "description"},
                {"data": "debit", "className": "text-right", "type": "num-fmt"},
                {"data": "credit", "className": "text-right", "type": "num-fmt"},
                {"data": "status", "className": "text-right"}
            ],
            "initComplete": function () {
                api = this.api()
                api.buttons().container()
                    .appendTo($('#deposit-withdrawals-table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');
            user = $('#user_id').val();
            currency = $('#currency').val();
            let route = `${$table.data('route')}/${user}/${currency}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    }

    // Points Transactions
    pointsTransactions() {
        let $button = $('#points-transactions');
        let $form = $('#points-transactions-form');

        $button.click(function () {
            $button.button('loading');

            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                data: $form.serialize()

            }).done(function (json) {
                $form.trigger('reset');
                $('form select').val(null).trigger('change');
                $('#points-balance').text(json.data.balance);
                $('#points-transactions-modal').modal('hide');
                swalSuccessWithButton(json);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }

    // Products users totals date
    productsUsersTotalsDate() {
        initDateRangePickerEndToday(open = 'right');
        let $table = $('#products-users-totals-date-table');
        let $button = $('#update-products-total-date');
        let startDate = $('#start_date').val();
        let endDate = $('#end_date').val();
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.totals"
            },
            "order": [[6, 'desc']],
            "columns": [
                {"data": "provider"},
                {"data": "provider_type"},
                {"data": "currency"},
                {"data": "bets", "className": "text-right", "type": "num-fmt"},
                {"data": "played", "className": "text-right", "type": "num-fmt"},
                {"data": "won", "className": "text-right", "type": "num-fmt"},
                {"data": "profit", "className": "text-right", "type": "num-fmt"},
                {"data": "rtp", "className": "text-right", "type": "num-fmt"},
                {"data": "hold", "className": "text-right", "type": "num-fmt"}
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#products-total-date-table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');
            startDate = $('#start_date').val();
            endDate = $('#end_date').val();
            let route = `${$table.data('route')}/${startDate}/${endDate}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    }

    // Reset password
    resetPassword() {
        let $button = $('#reset-password');
        let $form = $('#reset-password-form');

        $button.click(function () {
            $button.button('loading');

            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                data: $form.serialize()

            }).done(function (json) {
                $('#reset-password-modal').modal('hide');
                swalSuccessWithButton(json);
                $form.trigger('reset');

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }

    // Select2 users
    select2Users(placeholder) {
        $('select').select2();
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

    // Search users table
    searchTable() {
        let $table = $('#users-table');
        $table.DataTable({
            "order": []
        });
    }

    // Status user
    statusUser() {
        initSelect2();
        let api;
        let $table = $('#status-user-table');
        let $button = $('#search');
        let whitelabel = $('#whitelabel').val();
        let status = $('#status').val();
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.users",
            },
            "order": [],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "email"},
                {"data": "description"},
                {"data": "status"}
            ],
            "initComplete": function () {
                api = this.api()
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });
        $button.click(function () {
            $button.button('loading');
            whitelabel = $('#whitelabel').val();
            status = $('#status').val();
            console.log('paso', whitelabel, status)
            let route = `${$table.data('route')}/${whitelabel}/${status}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    }

    // Store users
    store() {
        let $button = $('#store');
        let $form = $('#users-form');
        clearForm($form);
        refreshRandomPassword();

        $button.click(function () {
            $button.button('loading');
            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                data: $form.serialize()

            }).done(function (json) {
                swalSuccessWithButton(json);
                clearForm($form);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }

    // Store main users
    storeMain() {
        initSelect2();
        clipboard();
        let $button = $('#store');
        let $form = $('#users-form');
        clearForm($form);

        $button.click(function () {
            $button.button('loading');

            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                data: $form.serialize()

            }).done(function (json) {

                swalSuccessWithButton(json);
                $form.trigger('reset');
                $('form select').val(null).trigger('change');
                $('.clipboard').attr('data-clipboard-text', json.data.password);
                $('#password').val(json.data.password);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }

    // Store claims
    storeClaims() {
        let $table = $('#store-claims-table');
        let $button = $('#store-claims-update');
        let user = $('#user_id').val();
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.claims"
            },
            "order": [],
            "columns": [
                {"data": "date"},
                {"data": "name"},
                {"data": "prize"},
                {"data": "points", "className": "text-right", "type": "num-fmt"},
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#store-claims-table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');
            user = $('#user_id').val();
            let route = `${$table.data('route')}/${user}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    }

    // Store transactions
    storeTransactions() {
        let $table = $('#store-transactions-table');
        let $button = $('#store-transactions-update');
        let user = $('#user_id').val();
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.transactions"
            },
            "order": [],
            "columns": [
                {"data": "date", "type": "date"},
                {"data": "provider"},
                {"data": "debit", "className": "text-right", "type": "num-fmt"},
                {"data": "credit", "className": "text-right", "type": "num-fmt"},
                {"data": "balance", "className": "text-right", "type": "num-fmt"}
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#store-transactions-table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');
            user = $('#user_id').val();
            let route = `${$table.data('route')}/${user}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    }

    //Transaction by lot
    transactionsByLot() {
        let $table = $('#transactions-lot-table');
        let $button = $('#transactions-by-lot');
        let $form = $('#transactions-by-lot-form');
        let $file = $('#transactions-by-lot-file');
        let api;
        $('.show').addClass('d-none');
        $button.click(function () {
            $button.button('loading');
            let formData = new FormData();
            formData.append('transaction-by-lot-file', $file[0].files[0]);
            $.ajax({
                url: $form.attr('action') + '?' + $form.serialize(),
                method: 'post',
                data: formData,
                processData: false,
                contentType: false
            }).done(function (json) {
                let data = json.data.transaction;
                $('.show').removeClass('d-none');
                $table.DataTable({
                    "data": data,
                    "order": [],
                    "columns": [
                        {"data": "username"},
                        {"data": "currency"},
                        {"data": "amount", "className": "text-right", "type": "num-fmt"},
                        {"data": "description"},
                        {"data": "attribute"},
                        {"data": "error"}
                    ],
                    "initComplete": function () {
                        api = this.api();
                        api.buttons().container()
                            .appendTo($('#transaction-lot-buttons'));
                    }
                });
            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }

    // Unlock balance
    unlockBalance() {
        let $button = $('#unclock');
        let $form = $('#unlock-balance-form');
        $button.click(function () {
            $button.button('loading');

            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                data: $form.serialize()

            }).done(function (json) {
                $('#unlock-balance-modal').modal('hide');
                swalSuccessNoButton(json);
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

    // Update profile
    updateProfile() {
        initDatepickerEndToday();

        let $button = $('#update-profile');
        let $form = $('#profile-form');
        $(".datepicker").datepicker({
            dateFormat: 'dd/mm/yy',
        });

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

    // Update user accounts
    updateUserAccounts() {
        let $button = $('#update-user-accounts');
        let $form = $('#update-user-accounts-form');
        let $modal = $('#edit-accounts-modal');

        $modal.on('show.bs.modal', function (event) {
            let $target = $(event.relatedTarget);
            let paymentMethodType = $target.data('payment-method-type')
            $('.account').addClass('d-none');
            $(`#${paymentMethodType}`).removeClass('d-none');
            $('#payment_method').val($target.data('payment-method'));
            $('#user_account_id').val($target.data('user-account-id'));

            switch (paymentMethodType) {
                case 'cryptocurrencies': {
                    $('#crypto_wallet').val($target.data('wallet'));
                    $('#crypto_currencies').val($target.data('crypto-currency'));
                    $('#network').val($target.data('network'));
                    break;
                }
                case 'wire-transfers': {
                    $('#account_number').val($target.data('account-number'));
                    $('#account_type').val($target.data('account-type'));
                    $('#social_reason').val($target.data('social-reason'));
                    $('#account_dni').val($target.data('dni'));
                    $('#bank_name').val($target.data('bank-name'));
                    $('#bank_id').val($target.data('bank-id'));
                    break;
                }
                case 'zelle':
                case 'electronic-wallets': {
                    $('input[name="account_email"]').val($target.data('email'));
                    $('#first_name_account').val($target.data('first-name'));
                    $('#last_name_account').val($target.data('last-name'));
                    break;
                }
                case 'vcreditos': {
                    $('#vcreditos_user').val($target.data('vcreditos-user'));
                    $('#vcreditos_secure_id').val($target.data('vcreditos-secure-id'));
                    break;
                }
                case 'bizum': {
                    $('#bizum_name').val($target.data('bizum-name'));
                    $('#bizum_phone').val($target.data('bizum-phone'));
                    break;
                }
                case 'binance': {
                    $('#binance_email').val($target.data('binance-email'));
                    $('#binance_phone').val($target.data('binance-phone'));
                    $('#binance_pay_id').val($target.data('binance-pay-id'));
                    $('#binance_id').val($target.data('binance-id'));
                    break;
                }
            }
        });

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
                $('#edit-accounts-modal').modal('hide');
                swalSuccessNoButton(json);
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

    // users audit
    usersAudit() {
        let $table = $('#audit-table');
        let $button = $('#update-audit');
        let user = $('#user_id').val();
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.users"
            },
            "order": [],
            "columns": [
                {"data": "details"},
                {"data": "types"},
                {"data": "date", "className": "text-right"},
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#audit-table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');
            user = $('#user_id').val();
            let route = `${$table.data('route')}/${user}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    }

    // users ips
    usersIps() {
        let $table = $('#ip-table');
        let $button = $('#update-ip');
        let user = $('#user_id').val();
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.ips"
            },
            "order": [
                [1, "desc"]
            ],
            "columns": [
                {"data": "ip"},
                {"data": "quantity", "className": "text-right"},
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#ip-table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');
            user = $('#user_id').val();
            let route = `${$table.data('route')}/${user}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    }

    // Users temp
    usersTemp() {
        let $table = $('#users-temp-table');
        let $button = $('#update');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.users"
            },
            "order": [],
            "columns": [
                {"data": "username"},
                {"data": "email"},
                {"data": "currency_iso"},
                {"data": "date"},
                {"data": "action", "className": "text-right"}
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
                Users.activateTemp(api, $table.data('route'));
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
            let route = `${$table.data('route')}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    };

    // Resend activate email
    resendActivateEmail() {
        let $button = $('#send-email');
        let $form = $('#resend-active-form');
        let $modal = $('#send-email-modal');

        $modal.on('show.bs.modal', function (event) {
            let $target = $(event.relatedTarget);
            $('#email').val($target.data('email'));
            $('#username').val($target.data('username'));

            $button.click(function () {
                $button.button('loading');

                $.ajax({
                    url: $form.attr('action'),
                    method: 'post',
                    data: $form.serialize()

                }).done(function (json) {
                    $('#send-email-modal').modal('hide');
                    swalSuccessWithButton(json);

                }).fail(function (json) {
                    swalError(json);

                }).always(function () {
                    $button.button('reset');
                });
            });
        })
    }

    // Wallet transactions
    walletTransactions() {
        let $table = $('#wallet-table');
        let $button = $('#update-wallet');
        let wallet = $('#wallet').val();
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.transactions"
            },
            "order": [[1, "desc"]],
            "columns": [
                {"data": "date", "type": "date"},
                {"data": "id"},
                {"data": "provider"},
                {"data": "description"},
                {"data": "debit", "className": "text-right", "type": "num-fmt"},
                {"data": "credit", "className": "text-right", "type": "num-fmt"},
                {"data": "balance", "className": "text-right", "type": "num-fmt"},
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons-wallet'));
            }
        });
        $button.click(function () {
            $button.button('loading');
            wallet = $('#wallet').val();
            let route = `${$table.data('route')}/${wallet}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    }

    // Wallet transactions historic
    walletTransactionsHistoric() {
        let $table = $('#wallet-table-historic');
        let $button = $('#update-wallet-historic');
        let wallet = $('#wallet-historic').val();
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.transactions"
            },
            "order": [],
            "columns": [
                {"data": "date"},
                {"data": "provider"},
                {"data": "description"},
                {"data": "debit", "className": "text-right", "type": "num-fmt"},
                {"data": "credit", "className": "text-right", "type": "num-fmt"},
                {"data": "balance", "className": "text-right", "type": "num-fmt"},
                //{"data": "type", "className": "text-right"}
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons-wallet-historic'));
            }
        });
        $button.click(function () {
            $button.button('loading');
            wallet = $('#wallet-historic').val();
            let route = `${$table.data('route')}/${wallet}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });
    }

    // Auto blocked users
    autoLockedUsers() {
        initDateRangePickerEndToday(open = 'right');
        let $table = $('#autolocked-users-table');
        let $button = $('#update');
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
                {"data": "currency"},
                {"data": "lock_date", "className": "text-right"},
                {"data": "unlock_date", "className": "text-right"},
                {"data": "lock_time", "className": "text-right"},
                {"data": "auto_locked", "className": "text-right"}
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
            let month = $('#month').val();
            let route = `${$table.data('route')}/${startDate}/${endDate}?month=${month}`;
            api.ajax.url(route).load();
            $table.on('draw.dt', function () {
                $button.button('reset');
            });
        });

    };

}

window.Users = Users;
