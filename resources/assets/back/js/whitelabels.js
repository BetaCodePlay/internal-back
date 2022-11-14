import {swalError, swalSuccessNoButton, swalSuccessWithButton} from "../../commons/js/core";
import {clearForm, initSelect2} from "./commons";

class Whitelabels {
    //currencies
    currencies(){
        initSelect2();
        $('#whitelabel').on('change', function () {
            let whitelabel = $(this).val();
            let route = $(this).data('route');
            let currency = $('#currency');

            if (whitelabel !== '') {
                $.ajax({
                    url: route,
                    type: 'get',
                    dataType: 'json',
                    data: {
                        whitelabel
                    }
                }).done(function (json) {
                    $('#currency option[value!=""]').remove();
                    $(json.data.currencies).each(function (key, element) {
                        currency.append("<option value=" + element.iso + ">" + element.iso + " (" + element.name + ")" + "</option>");
                    })
                    currency.prop('disabled', false);
                }).fail(function (json) {

                });
            } else {
                currency.val('');
            }
        });
    }

    // Operational balances
    operationalBalances() {
        let $table = $('#operational-balances-table');
        let route = $table.data('route');
        $table.DataTable({
            "ajax": {
                "url": route,
                "dataSrc": "data.operational_balances",
            },
            "order": [],
            "columns": [
                {"data": "description"},
                {"data": "currency_iso"},
                {"data": "balance", "className": "text-right", "type": "num-fmt"},
                {"data": "actions", "className": "text-right"},
            ],
            "initComplete": function () {
                let api = this.api()
                api.buttons().container()
                    .appendTo($('#table-buttons'));
                Whitelabels.updateOperationalBalances(api, route)
            }
        });
    }

    // Whitelabels status
    status() {
        let $table = $('#whitelabels-status-table');

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.whitelabels",
            },
            "order": [],
            "columns": [
                {"data": "description"},
                {"data": "domain"},
                {"data": "status_wl"},
                {"data": "status", "className": "d-flex justify-content-end form-inline"}
            ],
            "initComplete": function () {
                let api = this.api();
                api.buttons().container().appendTo($('#table-buttons'));
                Whitelabels.change($table.data('change-status'));
            }
        });
    };

    // Store whitelabel
    store() {
        initSelect2();
        let $button = $('#store');
        let $form = $('#whitelabels-form');
        clearForm($form);

        $button.click(function () {
            $button.button('loading');

            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                data: $form.serialize()

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

    // Change status
    static change(route) {

        $(document).on('change', '.change-whitelabels',function () {
            let status = $(this).val();
            let whitelabel = $(this).data('whitelabel');
            $.ajax({
                url: route,
                type: 'post',
                dataType: 'json',
                data: {
                    status,
                    whitelabel
                }
            }).done(function (json) {
                swalSuccessWithButton(json);

            }).fail(function (json) {
                swalError(json);
            });
        });
    }

    // Update operational balances
    static updateOperationalBalances(api, route) {
        let $button = $('#update');
        let $form = $('#update-operational-balance-form');
        let $modal = $('#update-operational-balance');

        $modal.on('show.bs.modal', function(event) {
            let $target = $(event.relatedTarget);
            $('#whitelabel').val($target.data('whitelabel'));
            $('#currency').val($target.data('currency'));
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

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }
}

window.Whitelabels = Whitelabels;
