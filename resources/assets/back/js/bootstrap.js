import {activeMenu, getCookie, setCookie} from './commons.js';

window.$ = window.jQuery = require('jquery');
window.Popper = require('popper.js').default;
require('bootstrap');
require('jquery.cookie');
require('../../commons/plugins/bootstrap-button/js/bootstrap-button.min');
require('datatables.net-dt');
require('datatables.net-responsive/js/dataTables.responsive');
// require('datatables.net-rowgroup');
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
    lengthMenu: [[25, 50, 100, 250, 500, 1000], [25, 50, 100, 250, 500, 1000]],
    processing: true,
    deferRender: true,
    responsive: true,
    language: {
        url: "/i18n/datatables/" + locale + ".lang"
    },
    buttons: {
        buttons: [
            {
                extend: 'excel',
                className: 'd-none d-sm-none d-md-block'
            },
            {
                extend: 'copy',
                className: 'd-none d-sm-none d-md-block',
                text: function (dt) {
                    return dt.i18n('buttons.copy', 'Copy');
                }
            }
        ]
    }
});

// Ucwords
String.prototype.ucwords = function () {
    let str = this.toLowerCase();
    return (str + '')
        .replace(/^(.)|\s+(.)/g, function ($letter) {
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
