import Swal from 'sweetalert2';
import ClipboardJs from 'clipboard';
import i18next from 'i18next';
import Backend from 'i18next-http-backend';
import swalLocale from './i18n/swal.json';
import {getCookie} from "../../back/js/commons";

// Clipboard
let clipboard = () => {
    $('button[data-clipboard-text]').tooltip('dispose');
    let clipboard = new ClipboardJs('button[data-clipboard-text]');
    clipboard.on('success', event => {
        $(event.trigger).tooltip('show');
        $(event.trigger).mouseleave(() => {
            setTimeout(() => {
                $(event.trigger).tooltip('dispose');
            }, 700);
        })
    });
};

let swalConfirm = (route, resolve) => {
    const swal = Swal.mixin({
        customClass: {
            confirmButton: 'btn u-btn-3d u-btn-primary mr-2',
            cancelButton: 'btn u-btn-3d u-btn-bluegray'
        },
        buttonsStyling: false
    });

    let locale = getCookie('language-js');
    locale = (locale === null || locale === '') ? 'en_US' : locale;

    i18next.use(Backend)
        .init({
            lng: locale,
            resources: swalLocale
        });

    swal.fire({
        title: i18next.t('are_you_sure'),
        text: i18next.t('action_cannot_be_undone'),
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="hs-admin-check"></i> ' + i18next.t('confirm'),
        cancelButtonText: '<i class="hs-admin-close"></i> ' + i18next.t('cancel'),
        preConfirm: () => {
            return fetch(route)
                .then(response => {
                    if (!response.ok) {
                        response.json().then(json => {
                            swal.fire({
                                title: json.data.title,
                                text: json.data.message,
                                type: 'error',
                                showConfirmButton: false,
                                showCancelButton: true,
                                cancelButtonText: '<i class="hs-admin-close"></i> ' + json.data.close
                            });
                        });
                    }
                    return response.json();
                });
        },
        allowOutsideClick: () => !swal.isLoading()

    }).then((result) => {
        if (result.value) {
            swalSuccessWithButton(result.value, resolve);
        }
    });
};

// Swal input
let swalInput = (route, resolve) => {
    let locale = getCookie('language-js');
    locale = (locale === null || locale === '') ? 'en_US' : locale;

    i18next.use(Backend)
        .init({
            lng: locale,
            resources: swalLocale
        });
    Swal.fire({
        title: i18next.t('Description'),
        input: 'text',
        inputAttributes: {
            autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: i18next.t('Confirm'),
        cancelButtonText: '<i class="hs-admin-close"></i> ' + i18next.t('cancel'),
        showLoaderOnConfirm: true,
        preConfirm: (description) => {
            return fetch(`${route}/${description}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(response.statusText)
                    }
                    return response.json()
                })
                .catch(error => {
                    Swal.showValidationMessage(
                        `Request failed: ${error}`
                    )
                })
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.dismiss == 'cancel') {
            $('.change-status').button('reset');
        } else {
            if (result.value.data.status !== '') {
                $('#status').val(result.value.data.status);

                if (result.value.data.status) {
                    $('#active-status').removeClass('d-none');
                    $('#inactive-status').addClass('d-none');
                } else {
                    $('#active-status').addClass('d-none');
                    $('#inactive-status').removeClass('d-none');
                }

                if (result.value.data.type == "0"){
                    setTimeout(() => {
                        window.location.href = '';
                    }, 1000);
                    swalSuccessNoButton(result.value);
                } else {
                    swalSuccessWithButton(result.value, resolve);
                }
            }
        }
    })
}

// Swal error
let swalError = json => {
    const swal = Swal.mixin({
        customClass: {
            cancelButton: 'btn u-btn-3d u-btn-bluegray'
        },
        buttonsStyling: false
    });

    let locale = getCookie('language-js');
    locale = (locale === null || locale === '') ? 'en_US' : locale;

    i18next.use(Backend)
        .init({
            lng: locale,
            resources: swalLocale
        });

    if (json.status === 422) {
        let html = '<ul>';
        $.each(json.responseJSON.errors, function (index, value) {
            html += '<li>' + value + '</li>';
        });
        html += '</li>';

        swal.fire({
            title: json.responseJSON.message,
            html: html,
            type: 'error',
            showConfirmButton: false,
            showCancelButton: true,
            cancelButtonText: '<i class="hs-admin-close"></i> ' + i18next.t('close')
        });

    } else {
        swal.fire({
            title: json.responseJSON.data.title,
            text: json.responseJSON.data.message,
            type: 'error',
            showConfirmButton: false,
            showCancelButton: true,
            cancelButtonText: '<i class="hs-admin-close"></i> ' + json.responseJSON.data.close
        });
    }
};

// Swal success no button
let swalSuccessNoButton = json => {
    Swal.fire({
        title: json.data.title,
        text: json.data.message,
        type: "success",
        showConfirmButton: false
    });
};

// Swal success with button
let swalSuccessWithButton = (json, resolve) => {

    const swal = Swal.mixin({
        customClass: {
            confirmButton: 'btn u-btn-3d u-btn-primary'
        },
        buttonsStyling: false
    });

    let alert = swal.fire({
        title: json.data.title,
        text: json.data.message,
        type: "success",
        confirmButtonText: '<i class="hs-admin-close"></i> ' + json.data.close
    });

    if (typeof resolve === 'function') {
        alert.then(resolve)
    }
};

// Swal validation
let swalValidation = (title, message, close) => {
    const swal = Swal.mixin({
        customClass: {
            cancelButton: 'btn u-btn-3d u-btn-bluegray'
        },
        buttonsStyling: false
    });

    swal.fire({
        title: title,
        text: message,
        type: 'error',
        showConfirmButton: false,
        showCancelButton: true,
        cancelButtonText: '<i class="hs-admin-close"></i> ' + close
    });
}

export {
    clipboard,
    swalConfirm,
    swalInput,
    swalSuccessNoButton,
    swalSuccessWithButton,
    swalError,
    swalValidation
};
