import {initSelect2} from "./commons";
import {swalError} from "../../commons/js/core";

class IQSoft  {
    // Search tickets
    search() {
        initSelect2();
        let api;
        let selectionsApi;
        let $table = $('#ticket-table');
        let $selectionsTable = $('#selections-table');
        let $button = $('#search');
        let $form = $('#ticket-search-form');

        $selectionsTable.DataTable({
            "ajax": {
                "url": $form.attr('action'),
                "dataSrc": "data.selections",
            },
            "order": [],
            "columns": [
                {"data": "event"},
                {"data": "bet_type"},
                {"data": "selection"},
                {"data": "quota", "className": "text-right"},
                {"data": "status", "className": "text-right"}
            ],
            "initComplete": function () {
                selectionsApi = this.api();
                selectionsApi.buttons().container()
                    .appendTo($('#selections-table-buttons'));
            }
        });

        $table.DataTable({
            "ajax": {
                "url": $form.attr('action'),
                "dataSrc": "data.ticket",
            },
            "order": [],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "date"},
                {"data": "provider_transaction"},
                {"data": "amount", "className": "text-right", "type": "num-fmt"},
                {"data": "win", "className": "text-right", "type": "num-fmt"},
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
                    selectionsApi.ajax.url(route).load();
                    $table.on('draw.dt', function () {
                        $button.button('reset');
                    });
                });

                $form.keypress(function (event) {
                    if (event.keyCode === 13) {
                        event.preventDefault();
                        $button.click();
                    }
                });
            }
        });

        $table.on('xhr.dt', function (event, settings, json, xhr) {
            if (xhr.status === 500 || xhr.status === 422 || xhr.status === 404) {
                swalError(xhr);
                $button.button('reset');
            }
        });
    }
}
window.IQSoft = IQSoft;
