import {getCookie, initDateRangePickerEndToday, initDateRangePickerEndTodayWithoutDate, initDateTimePicker, initFileInput, initRepeater, initSelect2, initTinyMCE} from "./commons";
import {swalConfirm, swalError, swalSuccessNoButton, swalSuccessWithButton, swalValidation} from "../../commons/js/core";
import i18next from "i18next";
import Backend from "i18next-http-backend";
import bonusSystemLocale from "./i18n/bonus-system.json";

import * as toastr from 'toastr';

class BonusSystem {

    // Add translations
    addTranslations(languages) {
        initTinyMCE();
        localStorage.clear();
        let $modal = $('#add-translations-modal');
        let languageIso;
        let locale = getCookie('language-js');
        locale = (locale === null || locale === '') ? 'en_US' : locale;

        $('#add-translations-form').keypress(function (event) {
            if (event.keyCode === 13) {
                event.preventDefault();
            }
        });

        i18next.use(Backend)
            .init({
                lng: locale,
                resources: bonusSystemLocale
            });

        for (let language of languages) {
            if (localStorage.getItem(`name-${language.iso}`)) {
                $(`a.add-translation[data-language-iso="${language.iso}"]`).addClass('d-none');
                $(`a.edit-translation[data-language-iso="${language.iso}"]`).removeClass('d-none');
            } else {
                $(`a.add-translation[data-language-iso="${language.iso}"]`).removeClass('d-none');
                $(`a.edit-translation[data-language-iso="${language.iso}"]`).addClass('d-none');
            }
        }

        $modal.on('show.bs.modal', function (event) {
            let $button = $(event.relatedTarget);
            $('#language-name').text($button.data('language'));
            languageIso = $button.data('language-iso');
            $('#name').val('');
            tinymce.get('content').setContent('');

            if (localStorage.getItem(`name-${languageIso}`)) {
                $('#name').val(localStorage.getItem(`name-${languageIso}`));
                tinymce.get('content').setContent(localStorage.getItem(`content-${languageIso}`));
            }
        });

        $('#save-translation').click(function () {
            let name = $('#name').val().trim();
            let content = tinymce.get('content').getContent().trim();
            let validationTitle = i18next.t('validation_title');
            let closeButton = i18next.t('close');

            if (name === '') {
                swalValidation(validationTitle, i18next.t('name'), closeButton);
                return;
            }
            if (content === '') {
                swalValidation(validationTitle, i18next.t('content'), closeButton);
                return;
            }

            localStorage.setItem(`name-${languageIso}`, name);
            localStorage.setItem(`content-${languageIso}`, content);
            $(`a.add-translation[data-language-iso="${languageIso}"]`).addClass('d-none');
            $(`a.edit-translation[data-language-iso="${languageIso}"]`).removeClass('d-none');
            toastr.options = {
                progressBar: true
            }
            $modal.modal('hide');
            toastr.success(i18next.t('added_translation'));
        });
    }

    // Add user campaign
    addUser() {
        initSelect2();

        let $button = $('#add-user');
        let $form = $('#campaigns-user-form');

        $form.on('submit', function (event) {
            event.preventDefault();
            tinymce.triggerSave();
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
                $('#add-bonus-modal').modal('hide');
                $form.trigger('reset');
                $('form select').val(null).trigger('change');
                swalSuccessNoButton(json);
                setTimeout(() => {
                    window.location.href = json.data.route;
                }, 500)

            }).fail(function (json) {
                console.log(json)
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }

    // All campaigns
    all() {
        let $table = $('#campaigns-table');
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.campaigns"
            },
            "order": [[0, "desc"]],
            "columns": [
                {"data": "id"},
                {"data": "name"},
                {"data": "allocation_criteria"},
                {"data": "dates"},
                {"data": "currency_iso", "className": "text-right"},
                {"data": "status", "className": "text-right"},
                {"data": "actions", "className": "text-right"}
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
            }
        });
    }

    // Allocation criteria types
    allocationCriteriaTypes() {
        initSelect2();
        $('#allocation_criteria, #currency').on('change', function () {
            let allocationCriteria = $('#allocation_criteria').val();
            let route = $('#allocation_criteria').data('route');
            let $campaigns = $('#campaigns');
            let currency = $('#currency').val();

            if (allocationCriteria !== '') {
                $campaigns.find('option[value!="*"]').remove();
                $.ajax({
                    url: route,
                    type: 'get',
                    dataType: 'json',
                    data: {
                        allocation_criteria: allocationCriteria,
                        currency: currency
                    }
                }).done(function (json) {
                    $(json.data.campaigns_data).each(function (key, element) {
                        let option = new Option(element.name, element.id, false, false);
                        $campaigns.append(option).trigger('change');
                    })
                });
            } else {
                $campaigns.val('');
            }
        });
    }

    actualizarValor(checkbox) {
        checkbox.setAttribute('value', checkbox.checked ? 'true' : 'false');
    }

    // Campaigns
    campaigns() {
        initSelect2();
        initDateRangePickerEndTodayWithoutDate(open = 'right');
        let $table = $('#campaigns-table');
        let $button = $('#search');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.campaigns"
            },
            "order": [[5, "desc"]],
            "columns": [
                {"data": "campaign_id"},
                {"data": "campaign"},
                {"data": "vertical"},
                {"data": "currency_iso"},
                {"data": "used_bonus", "className": "text-right"},
                {"data": "converted_bonus", "className": "text-right"},
                {"data": "ended_bonus", "className": "text-right"},
                {"data": "active_bonus", "className": "text-right"},
                {"data": "deposited_amount", "className": "text-right"},
                {"data": "criteria_met", "className": "text-right"},
                {"data": "claimed", "className": "text-right"},
                {"data": "active_users", "className": "text-right"},
                {"data": "promo_code"},
                {"data": "dates", "className": "text-right"}
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');
            let allocation_criteria = $('#allocation_criteria').val();
            let convert = $('#convert').val();
            let campaigns = $('#campaigns').val();
            let startDate = $('#start_date').val();
            let endDate = $('#end_date').val();
            let currency = $('#currency').val();
            let route = `${$table.data('route')}?allocation_criteria=${allocation_criteria}&convert=${convert}&campaigns=${campaigns}&start_date=${startDate}&end_date=${endDate}&currency=${currency}`;
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
            $('#used-bonus').text(json.data.general_totals.used_bonus);
            $('#ended-bonus').text(json.data.general_totals.ended_bonus);
            $('#active-bonus').text(json.data.general_totals.active_bonus);
            $('#converted-bonus').text(json.data.general_totals.converted_bonus);
            $('#amount-deposited').text(json.data.general_totals.deposited_amount);
        });
    }

    // Campaign user participation
    campaignsUserParticipation() {
        initDateRangePickerEndToday(open = 'right');
        initSelect2();
        let $table = $('#user-participation-table');
        let $button = $('#search');
        let api;

        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.users"
            },
            "order": [[7, "desc"]],
            "columns": [
                {"data": "user"},
                {"data": "username"},
                {"data": "campaign"},
                {"data": "vertical"},
                {"data": "currency"},
                {"data": "used_bonus", "className": "text-right"},
                {"data": "converted_bonus", "className": "text-right"},
                {"data": "ended_bonus", "className": "text-right"},
                {"data": "active_bonus", "className": "text-right"},
                {"data": "deposited_amount", "className": "text-right"},
                {"data": "user_deposit_history", "className": "text-right"},
                {"data": "user_withdrawal_history", "className": "text-right"},
                {"data": "profit", "className": "text-right"},
                {"data": "percentage", "className": "text-right"},
                {"data": "dates", "className": "text-right"}
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
            }
        });

        $button.click(function () {
            $button.button('loading');
            let allocation_criteria = $('#allocation_criteria').val();
            let status = $('#status').val();
            let currency = $('#currency').val();
            let convert = $('#convert').val();
            let startDate = $('#start_date').val();
            let endDate = $('#end_date').val();
            let campaign_data = $('#campaign_data').val();
            let route = `${$table.data('route')}/${startDate}/${endDate}?allocation_criteria=${allocation_criteria}&currency=${currency}&convert=${convert}&status=${status}&campaign_data=${campaign_data}`;
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
            $('#used-bonus').text(json.data.general_totals.used_bonus);
            $('#ended-bonus').text(json.data.general_totals.ended_bonus);
            $('#active-bonus').text(json.data.general_totals.active_bonus);
            $('#converted-bonus').text(json.data.general_totals.converted_bonus);
            $('#amount-deposited').text(json.data.general_totals.deposited_amount);
        });
    }

    // Fill restriction select
    fillSelects(field, values) {
        for (let value of values) {
            let option = new Option(value.title, value.id, true, true);
            $(field).append(option).trigger('change');
        }
    }

    // Manual adjustments
    manualAdjustments() {
        let $button = $('#manual-adjustment-bonus');
        let $form = $('#manual-adjustments-bonus-form');

        $button.click(function () {
            $button.button('loading');

            $.ajax({
                url: $form.attr('action'),
                method: 'post',
                data: $form.serialize()

            }).done(function (json) {
                $form.trigger('reset');
                $('form select').val(null).trigger('change');
                $('#manual-adjustments-bonus-modal').modal('hide');
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

    // Remove user
    removeUser() {
        let $table = $('#campaign-bonus-user-table');
        let api;
        $table.DataTable({
            "ajax": {
                "url": $table.data('route'),
                "dataSrc": "data.campaigns"
            },
            "order": [],
            "columns": [
                {"data": "name"},
                {"data": "actions", "className": "text-right"},
            ],
            "initComplete": function () {
                api = this.api();
                api.buttons().container()
                    .appendTo($('#table-buttons'));
                $(document).on('click', '.delete', function () {
                    let $button = $(this);
                    swalConfirm($button.data('route'), function (json) {
                        $table.DataTable().ajax.url($table.data('route')).load();
                        $('#remover-bonus-modal').modal('hide');
                        setTimeout(() => {
                            window.location.href = json.data.route;
                        }, 500)
                    });
                });
            }
        });
    }

    // Set translations
    setTranslations(languages, translations) {
        localStorage.clear();

        for (let language of languages) {
            let iso = language.iso;
            if (translations[iso] !== undefined) {
                $(`a.add-translation[data-language-iso="${iso}"]`).addClass('d-none');
                $(`a.edit-translation[data-language-iso="${iso}"]`).removeClass('d-none');

                localStorage.setItem(`name-${iso}`, translations[iso].name);
                localStorage.setItem(`content-${iso}`, translations[iso].description);
            }
        }
    }

    // Store
    store(languages) {
        initDateTimePicker();
        initSelect2();
        initFileInput();
        initRepeater();
        BonusSystem.bonusType();
        BonusSystem.bets();
        BonusSystem.deposits();
        BonusSystem.paymentMethods();
        BonusSystem.providers();
        BonusSystem.providersBets();
        BonusSystem.providerTypes();
        BonusSystem.rollovers();
        BonusSystem.sportsProvider();
        BonusSystem.usersRestrictionType();
        BonusSystem.fillUsersRestriction('#include_users');
        BonusSystem.fillUsersRestriction('#exclude_users');
        BonusSystem.registration();
        let $form = $('#campaigns-form');
        let $button = $('#store');

        $('#currencies').on('change', function () {
            let currencies = $(this).val();
            $('.deposits-row').addClass('d-none');
            $('.bonus-row').addClass('d-none');
            $('.bet-row').addClass('d-none');
            $(`.deposit-row-${currencies}`).removeClass('d-none');
            $(`.bonus-row-${currencies}`).removeClass('d-none');
            $(`.bet-row-${currencies}`).removeClass('d-none');

            //Si es multiple currencies se agrega est for
            // for (let currency of currencies) {
            //     $(`.deposit-row-${currency}`).removeClass('d-none');
            //     $(`.bonus-row-${currency}`).removeClass('d-none');
            //     $(`.bet-row-${currency}`).removeClass('d-none');
            // }

            if (currencies.length > 0) {
                $('#deposits').removeClass('disabled').removeAttr('disabled').parent().removeClass('disabled');
                $('#bets').removeClass('disabled').removeAttr('disabled').parent().removeClass('disabled');
            } else {
                $('#bets').addClass('disabled').attr('disabled', true).parent().addClass('disabled');
                $('#bets').addClass('disabled').attr('disabled', true).parent().addClass('disabled');
            }
        });

        $form.on('submit', function (event) {
            event.preventDefault();
            tinymce.triggerSave();
            let formData = new FormData(this);
            let translations = BonusSystem.getTranslations(languages);
            if (!translations) {
                return translations;
            }
            $button.button('loading');
            formData.append('translations', JSON.stringify(translations));

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
                localStorage.clear();
                $('.add-translation').removeClass('d-none');
                $('.edit-translation').addClass('d-none');
                swalSuccessWithButton(json);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }

    // Update pages
    update(languages, list) {
        initDateTimePicker()
        initSelect2();
        initFileInput();
        initRepeater(list);
        BonusSystem.bonusType();
        BonusSystem.bets();
        BonusSystem.deposits();
        BonusSystem.paymentMethods();
        BonusSystem.providers();
        BonusSystem.providersBets();
        BonusSystem.providerTypes();
        BonusSystem.rollovers();
        BonusSystem.sportsProvider();
        BonusSystem.usersRestrictionType();
        BonusSystem.fillUsersRestriction('#include_users');
        BonusSystem.fillUsersRestriction('#exclude_users');
        BonusSystem.registration();
        let $form = $('#campaigns-form');
        let $button = $('#update');

        $form.on('submit', function (event) {
            event.preventDefault();
            tinymce.triggerSave();
            let formData = new FormData(this);
            let translations = BonusSystem.getTranslations(languages);
            if (!translations) {
                return translations;
            }
            $button.button('loading');
            formData.append('translations', JSON.stringify(translations));

            $.ajax({
                url: $form.attr('action'),
                type: 'post',
                dataType: 'json',
                contentType: false,
                processData: false,
                cache: false,
                data: formData

            }).done(function (json) {
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

    // Provider types
    versions() {
        initSelect2();
        $('#versions').on('change', function () {
            let versions = $('#versions').val();
            let route = `${$(this).data('route')}/?versions=${versions}`;

            if (versions !== '') {
                setTimeout(() => {
                    window.location.href = route;
                }, 1000);
            } else {
                versions.val('');
            }
        });
    }

    // Bets
    static bets() {
        $('#bets').change(function () {
            let $table = $('.bets-table');

            if (this.checked) {
                $table.removeClass('d-none');
                $('#nim_bet').removeClass('disabled').removeAttr('disabled').parent().removeClass('disabled');

            } else {
                $table.addClass('d-none');
                $('#nim_bet').addClass('disabled').attr('disabled', true).parent().addClass('disabled');
            }
        });
    }

    // Bonus type radios
    static bonusType() {
        $('#fixed-bonus').change(function () {
            if (this.checked) {
                $('.fixed-bonus, .max-convert, .bonus-table').removeClass('d-none');
                $('.deposit-percentage').addClass('d-none');
            }
        });

        $('#deposit-percentage').change(function () {
            console.log('Estoy pasando por el deposit-percentage');
            if (this.checked) {
                console.log('Estoy pasando por el deposit-percentage despues del checked');
                $('.deposit-percentage, .max-convert, .bonus-table').removeClass('d-none');
                $('.fixed-bonus').addClass('d-none');
            }
        });
    }

    // Deposits
    static deposits() {
        $('#deposits').change(function () {
            let $table = $('.deposits-table');

            if (this.checked) {
                $table.removeClass('d-none');
                $('#deposit-percentage').removeClass('disabled').removeAttr('disabled').parent().removeClass('disabled');

            } else {
                $table.addClass('d-none');
                $('#deposit-percentage').addClass('disabled').attr('disabled', true).parent().addClass('disabled');
            }
        });
    }

    // Fill users restriction
    static fillUsersRestriction($field) {
        $($field).select2({
            width: '100%',
            allowClear: true,
            ajax: {
                type: "POST",
                url: $($field).data('route'),
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        user: params.term,
                        page: params.page
                    };
                },
                processResults: function (json) {
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
                return repo.text;
            },
            escapeMarkup: function (markup) {
                return markup;
            },
            templateResult: function (res) {
                if (res.loading) {
                    return res.text;
                }
                return "<div class='select2-result-repository clearfix'>" +
                    "<div class='select2-result-repository__meta'>" +
                    "<div class='select2-result-repository__title'>" + res.text + "</div>" +
                    "</div></div>";
            }
        });
    }

    // Get translations
    static getTranslations(languages) {
        let translations = {};
        let translationsQuantity = 0;

        for (let language of languages) {
            let iso = language.iso;
            if (localStorage.getItem(`name-${iso}`)) {
                let newLanguage = {
                    [language.iso]: {
                        name: localStorage.getItem(`name-${iso}`),
                        description: localStorage.getItem(`content-${iso}`)
                    }
                };
                Object.assign(translations, newLanguage);
                translationsQuantity++;
            }
        }

        if (translationsQuantity === 0) {
            swalValidation(i18next.t('validation_title'), i18next.t('add_translation'), i18next.t('close'));
            return false;
        }
        return translations;
    }

    // Payment methods
    static paymentMethods() {
        // $('#currencies').on('change', function () {
        //     let currencies = $(this).val();
        //     let route = $(this).data('payments-route');

        //     if (currencies.length > 0) {
        //         $.ajax({
        //             url: route,
        //             type: 'get',
        //             dataType: 'json',
        //             data: {
        //                 currencies: currencies
        //             }
        //         }).done(function (json) {
        //             let paymentMethods = json.data.payment_methods;

        //             for (let currency of currencies) {
        //                 $(`#include-payment-methods-${currency} option[value!="1"], #exclude-payment-methods-${currency} option[value!="1"]`).remove();

        //                 for (let paymentMethod in paymentMethods) {
        //                     if (paymentMethod === currency) {
        //                         for (let currencyPaymentMethod of paymentMethods[paymentMethod]) {
        //                             let include = new Option(currencyPaymentMethod.name, currencyPaymentMethod.id, false, false);
        //                             $(`#include-payment-methods-${currency}`).append(include).trigger('change');
        //                             let exclude = new Option(currencyPaymentMethod.name, currencyPaymentMethod.id, false, false);
        //                             $(`#exclude-payment-methods-${currency}`).append(exclude).trigger('change');
        //                         }
        //                     }
        //                 }
        //             }
        //         });
        //     }
        // });
        $('#currencies').on('change', function () {
            let currencies = [$(this).val()];
            let route = $(this).data('payments-route');

            if (currencies.length > 0) {
                $.ajax({
                    url: route,
                    type: 'get',
                    dataType: 'json',
                    data: {
                        currencies: currencies
                    }
                }).done(function (json) {
                    let paymentMethods = json.data.payment_methods;

                    for (let currency of currencies) {
                        $(`#include-payment-methods-${currency} option[value!="1"], #exclude-payment-methods-${currency} option[value!="1"]`).remove();

                        for (let paymentMethod in paymentMethods) {
                            if (paymentMethod === currency) {
                                for (let currencyPaymentMethod of paymentMethods[paymentMethod]) {
                                    let include = new Option(currencyPaymentMethod.name, currencyPaymentMethod.id, false, false);
                                    $(`#include-payment-methods-${currency}`).append(include).trigger('change');
                                    let exclude = new Option(currencyPaymentMethod.name, currencyPaymentMethod.id, false, false);
                                    $(`#exclude-payment-methods-${currency}`).append(exclude).trigger('change');
                                }
                            }
                        }
                    }
                });

        });
    }

    // Providers
    static providers() {
        initSelect2();
        $('#provider_type').on('change', function () {
            let provider = $('#provider_type').val();
            let route = $(this).data('route');
            let $excludeProvider = $('#exclude_providers');
            $excludeProvider.find('option[value!=""]').remove();

            if (provider !== '') {
                $.ajax({
                    url: route,
                    type: 'get',
                    dataType: 'json',
                    data: {
                        providers: provider
                    }
                }).done(function (json) {
                    $(json.data.exclude_providers).each(function (key, element) {
                        let option = new Option(element.name, element.id, false, false);
                        $excludeProvider.append(option).trigger('change')
                    })
                });
            }
        });
    }

    // Providers by bets
    static providersBets() {
        initSelect2();
        $('#provider_type_bet').on('change', function () {
            let provider = $('#provider_type_bet').val();
            let currencies = $('#currencies').val();
            let route = $(this).data('route');
            let $excludeProvider = $('#exclude_providers_bet');
            $excludeProvider.find('option[value!=""]').remove();

            if (provider !== '') {
                $.ajax({
                    url: route,
                    type: 'get',
                    dataType: 'json',
                    data: {
                        providers: provider
                    }
                }).done(function (json) {
                    let excludeProviders = json.data.exclude_providers;

                    for (let currency of currencies) {
                        $(excludeProviders).each(function (key, element) {
                            let option = new Option(element.name, element.id, false, false);
                            $(`#exclude_providers_bet-${currency}`).append(option).trigger('change')
                        })

                    }
                });
            }
        });
    }

    // Provider types
    static providerTypes() {
        initSelect2();
        $('#currencies').on('change', function () {
            let currencies = $(this).val();
            let route = $(this).data('route');
            let $providerTypes = $('#provider_type');
            let $providerTypesBets = $('#provider_type_bet');
            $providerTypes.find('option[value!=""]').remove();
            $providerTypesBets.find('option[value!=""]').remove();

            if (currencies.length > 0) {
                $.ajax({
                    url: route,
                    type: 'get',
                    dataType: 'json',
                    data: {
                        currencies: currencies
                    }
                }).done(function (json) {
                    $(json.data.provider_types).each(function (key, element) {
                        let option = new Option(element.name, element.id, false, false);
                        $providerTypes.append(option).trigger('change')
                    });
                    $(json.data.provider_types).each(function (key, element) {
                        let option = new Option(element.name, element.id, false, false);
                        $providerTypesBets.append(option).trigger('change')
                    });
                });
            }
        });
    }

    // Registration
    static registration() {
        $('#registration').change(function () {

            if (this.checked) {
                $('#deposits').addClass('disabled').attr('disabled', true).parent().addClass('disabled');

            } else {
                $('#deposits').removeClass('disabled').removeAttr('disabled').parent().removeClass('disabled');
            }
        });
    }

    // Complete rollovers
    static rollovers() {
        $('#complete_rollovers').change(function () {
            switch ($(this).val()) {
                case 'true':
                case 'yes': {
                    $('.rollovers-data').removeClass('d-none');
                    break;
                }
                case 'false':
                case 'no':{
                    $('.rollovers-data').addClass('d-none');
                    break;
                }
                default: {
                    $('.rollovers-data').addClass('d-none');
                    break;
                }
            }
        });
    };

    // Sports provider
    static sportsProvider() {
        $('#provider_type').change(function () {
            $('.sports').addClass('d-none');

            if ($(this).val() == '10') {
                $('.sports').removeClass('d-none');
            }
        });
    };

    // Users restriction type
    static usersRestrictionType() {
        $('#users_restriction_type').change(function () {
            switch ($(this).val()) {
                case 'users': {
                    $('.search-users').removeClass('d-none');
                    $('.search-segments, .search-excel').addClass('d-none');
                    break;
                }
                case 'segments': {
                    $('.search-segments').removeClass('d-none');
                    $('.search-users, .search-excel').addClass('d-none');
                    break;
                }
                case 'excel': {
                    $('.search-excel').removeClass('d-none');
                    $('.search-users, .search-segments').addClass('d-none');
                    break;
                }
                default: {
                    $('.search-users, .search-segments, .search-excel').addClass('d-none');
                    break;
                }
            }
        });
    };
}

window.BonusSystem = BonusSystem;
