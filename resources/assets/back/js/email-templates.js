import {initSelect2} from "./commons";
import {swalConfirm, swalError, swalSuccessNoButton, swalSuccessWithButton} from "../../commons/js/core";

class EmailTemplates {
    // All templates
    all() {
        let $table = $('#sliders-table');
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.templates"
            },
            "order": [],
            "columns": [
                {"data": "title"},
                {"data": "subject"},
                {"data": "language"},
                {"data": "currency_iso"},
                {"data": "status", "className": "text-right"},
                {"data": "actions", "className": "text-right"},
            ],
            "initComplete": function () {
                let api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));

                EmailTemplates.testEmail(api, $table.data('route'))
                $(document).on('click', '.delete', function () {
                    let $button = $(this);
                    swalConfirm($button.data('route'), function () {
                        $table.DataTable().ajax.url($table.data('route')).load();
                    });
                });

                $(document).on('click', '.duplicate', function () {
                    let $button = $(this);
                    swalConfirm($button.data('route'), function () {
                        setTimeout(() => window.location.href = $button.data('route-edit'), 1000);
                    });
                });
            }
        });
    }

    // All transactions templates
    allTransactions() {
        let $table = $('#email-templates-transaction-table');
        let $button= $('#update');
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.templates"
            },
            "order": [],
            "columns": [
                {"data": "title"},
                {"data": "subject"},
                {"data": "language"},
                {"data": "currency_iso"},
                {"data": "status", "className": "text-right"},
                {"data": "actions", "className": "text-right"},
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

                $(document).on('click', '.duplicate', function () {
                    let $button = $(this);
                    swalConfirm($button.data('route'), function () {
                        setTimeout(() => window.location.href = $button.data('route-edit'), 1000);
                    });
                });

                $button.click(function () {
                    $button.button('loading');
                    let email_templates_type_id = $('#email_templates_type_id').val();
                    let route = `${$table.data('route')}?email_templates_type_id=${email_templates_type_id}`;
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
        });
    }

    // Preview template
    preview() {
        $('#template-preview-modal').on('show.bs.modal', function (event) {
            let $button = $(event.relatedTarget);
            let html = $button.data('html');
            let iframe = $('#html').get(0);
            var iframedoc = iframe.document;
            if (iframe.contentDocument) {
                iframedoc = iframe.contentDocument;
            } else if (iframe.contentWindow) {
                iframedoc = iframe.contentWindow.document;
            }
            iframedoc.open();
            iframedoc.writeln(html);
            iframedoc.close();

            $('#html').load(function() {
                console.log($('#html').contents().height());
            });
        });
    }

    // Store template
    store() {
        initSelect2();
        let $button = $('#store');
        let $form = $('#email-templates-form');

        $button.click(function () {
            $button.button('loading');
            document.getElementById('mosaico').contentWindow.postMessage({action: 'save'}, '*');
            window.addEventListener('message', function (event) {
                let eventData = event.data;
                $.ajax({
                    url: $form.attr('action'),
                    method: 'post',
                    data: {
                        title: $('#title').val(),
                        subject: $('#subject').val(),
                        language: $('#language').val(),
                        currency: $('#currency').val(),
                        status: $('#status').val(),
                        template: eventData.template,
                        metadata: eventData.metadata,
                        html: eventData.html
                    }
                }).done(function (json) {
                    swalSuccessNoButton(json);
                    setTimeout(() => window.location.href = json.data.route, 1000);

                }).fail(function (json) {
                    swalError(json);

                }).always(function () {
                    $button.button('reset');
                });
            });
        });
    }

    // Test email
    static  testEmail(api, route) {
        console.log(api, route)
        let $modal = $('#test-mail-modal');
        let $button = $('#test-button');
        let $form = $('#test-email-form');

        $modal.on('show.bs.modal', function(event) {
            let $target = $(event.relatedTarget);
            $('#template_id').val($target.data('template'));
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

    // Store transaction template
    storeTransaction() {
        initSelect2();
        let $button = $('#store');
        let $form = $('#email-templates-transaction-form');

        $button.click(function () {
            $button.button('loading');
            document.getElementById('mosaico').contentWindow.postMessage({action: 'save'}, '*');
            window.addEventListener('message', function (event) {
                let eventData = event.data;
                $.ajax({
                    url: $form.attr('action'),
                    method: 'post',
                    data: {
                        title: $('#title').val(),
                        subject: $('#subject').val(),
                        language: $('#language').val(),
                        currency: $('#currency').val(),
                        status: $('#status').val(),
                        email_templates_type_id: $('#email_templates_type_id').val(),
                        template: eventData.template,
                        metadata: eventData.metadata,
                        html: eventData.html
                    }
                }).done(function (json) {
                    swalSuccessNoButton(json);
                    setTimeout(() => window.location.href = json.data.route, 1000);

                }).fail(function (json) {
                    swalError(json);

                }).always(function () {
                    $button.button('reset');
                });
            });
        });
    }

    // Update templates
    update(metadata, content) {
        initSelect2();
        let $button = $('#update');
        let $form = $('#email-templates-form');

        document.getElementById('mosaico').contentWindow.postMessage({
            action: 'edit',
            metadata,
            content
        }, '*');

        $button.click(function () {
            $button.button('loading');
            document.getElementById('mosaico').contentWindow.postMessage({
                action: 'update',
                key: metadata.key
            }, '*');
            window.addEventListener('message', function (event) {
                let eventData = event.data;
                $.ajax({
                    url: $form.attr('action'),
                    method: 'post',
                    data: {
                        id: $('#id').val(),
                        title: $('#title').val(),
                        subject: $('#subject').val(),
                        language: $('#language').val(),
                        currency: $('#currency').val(),
                        status: $('#status').val(),
                        template: eventData.template,
                        metadata: eventData.metadata,
                        html: eventData.html
                    }
                }).done(function (json) {
                    swalSuccessWithButton(json);

                }).fail(function (json) {
                    swalError(json);

                }).always(function () {
                    $button.button('reset');
                });
            });
        });
    }

    // Update templates
    updateTransaction(metadata, content) {
        initSelect2();
        let $button = $('#update');
        let $form = $('#email-templates-transaction-form');

        document.getElementById('mosaico').contentWindow.postMessage({
            action: 'edit',
            metadata,
            content
        }, '*');

        $button.click(function () {
            $button.button('loading');
            document.getElementById('mosaico').contentWindow.postMessage({
                action: 'update',
                key: metadata.key
            }, '*');
            window.addEventListener('message', function (event) {
                let eventData = event.data;
                $.ajax({
                    url: $form.attr('action'),
                    method: 'post',
                    data: {
                        id: $('#id').val(),
                        title: $('#title').val(),
                        subject: $('#subject').val(),
                        language: $('#language').val(),
                        currency: $('#currency').val(),
                        status: $('#status').val(),
                        email_templates_type_id: $('#email_templates_type_id').val(),
                        template: eventData.template,
                        metadata: eventData.metadata,
                        html: eventData.html
                    }
                }).done(function (json) {
                    swalSuccessWithButton(json);

                }).fail(function (json) {
                    swalError(json);

                }).always(function () {
                    $button.button('reset');
                });
            });
        });
    }
}

window.EmailTemplates = EmailTemplates;
