import {activeMenu, getCookie, setCookie} from './commons.js';

window.$ = window.jQuery = require('jquery');
window.Popper = require('popper.js').default;
require('jszip');
require('pdfmake');
require('bootstrap');
require('jquery.cookie');
require('../../commons/plugins/bootstrap-button/js/bootstrap-button.min');
require('datatables.net-dt');
require('datatables.net-buttons-dt');
require('datatables.net-buttons/js/buttons.html5.js');
require('datatables.net-buttons/js/buttons.print.js');
require('datatables.net-responsive-dt');
require('jquery-mousewheel');
require('malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar');
activeMenu();

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// DataTables global settings
let locale = getCookie('language-js');
locale = (locale === null || locale === '') ? 'en_US' : locale;

$.fn.dataTable.ext.errMode = 'throw';

$.extend(true, $.fn.dataTable.defaults, {
    dom: '<"datatable-header"lBfr><"datatable-body"t><"datatable-footer"ip>',
    lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
    processing: true,
    deferRender: true,
    responsive: true,
    searching: true,
    paging: true,
    buttons: ['copy', 'excel', 'pdf', 'print'],
    language: {
        url: "/i18n/datatables/" + locale + ".lang"
    },
});

// Ucwords
String.prototype.ucwords = function () {
    let str = this.toLowerCase();
    return (str + '')
        .replace(/^(.)|\s+(  )*(  )/g, function ($letter) {
            return $letter.toUpperCase()
        })
};

// Change language
$('.change-language').click(function () {
    let locale = $(this).data('locale');
    setCookie('language-js', locale, 365);
})

// Change timezone
$('select').select2({
    'z-index': 1000,
    'width': '100%'
});

$('.change-timezone').change(function () {
    let timezone = $(this).val();
    let route = $(this).data('route');

    $.ajax({
        url: route,
        type: 'post',
        dataType: 'json',
        data: {
            timezone
        }
    }).done(function() {
        window.location.reload();
    });
})
