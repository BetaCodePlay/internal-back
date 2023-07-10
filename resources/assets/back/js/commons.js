import tinymce from 'tinymce';
import moment from 'moment';
import Litepicker from 'litepicker';
import i18next from "i18next";
import Backend from 'i18next-http-backend';
import dateRangepickerLocale from './i18n/daterangepicker.json';

require('bootstrap-daterangepicker');
require('bootstrap-fileinput');
require('bootstrap-datepicker');
require('bootstrap-datepicker/js/locales/bootstrap-datepicker.es');
require('bootstrap-datepicker/js/locales/bootstrap-datepicker.pt');
require('bootstrap-datepicker/js/locales/bootstrap-datepicker.he');
require('bootstrap-datepicker/js/locales/bootstrap-datepicker.tr');
require('bootstrap-fileinput/js/locales/es.js');
require('bootstrap-fileinput/js/locales/pt.js');
require('bootstrap-fileinput/js/locales/he.js');
require('bootstrap-fileinput/js/locales/tr.js');
require('bootstrap-fileinput/js/plugins/piexif');
require('bootstrap-fileinput/themes/fa6/theme');
require('jquery-datetimepicker/build/jquery.datetimepicker.full.js');
require('jquery.repeater/jquery.repeater');
require('select2');

// Active current menu
let activeMenu = () => {
    let url = window.location.href;
    let $item = $(`a[href="${url}"]`);
    $item.addClass('active');
    $item.parents('li').addClass('has-active u-side-nav-opened');
    $item.parents('li').find('.collapse').eq(0).addClass('show');
};

// Clear form
let clearForm = $form => {
    $('#clear').click(function () {
        $form.trigger('clear');
        $form.trigger('reset');
        $form.find('select').val(null).trigger('change');
        refreshRandomPassword();
    });
};

// Get cookie
let getCookie = name => {
    name = name + "=";
    let ca = document.cookie.split(';');

    for (var i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
};

// Init datepicker end today
let initDatepickerEndToday = () => {
    let locale = getCookie('language-js');
    locale = (locale === null || locale === '') ? 'en_US' : locale;
    locale = locale.substr(0, 2);

    $('.datepicker').datepicker({
        language: locale,
        autoclose: true,
        todayBtn: true,
        todayHighlight: true,
        endDate: '0d',
        format: 'dd-mm-yyyy',
        orientation: 'bottom'
    });
};

// Init datepicker start today
let initDatepickerStartToday = () => {
    let locale = getCookie('language-js');
    locale = (locale === null || locale === '') ? 'en_US' : locale;
    locale = locale.substr(0, 2);

    $('.datepicker').datepicker({
        language: locale,
        autoclose: true,
        todayBtn: true,
        todayHighlight: true,
        startDate: '0d',
        format: 'dd-mm-yyyy',
        orientation: 'bottom'
    });
};

// Init datepicker start today
let initDateTimePicker = () => {
    let locale = getCookie('language-js');
    locale = (locale === null || locale === '') ? 'en_US' : locale;
    locale = locale.substr(0, 2);

    $.datetimepicker.setLocale(locale);
    $('.datetimepicker').datetimepicker({
        format: 'd-m-Y h:i a',
        inline: false,
        lang: locale,
        closeOnWithoutClick: true,
        validateOnBlur: false
    });
};

// Init date range picker end today
let initDateRangePickerEndMonth = (open = 'left') => {
    let locale = getCookie('language-js');
    locale = (locale === null || locale === '') ? 'en_US' : locale;
    let $dateRange = $('.daterange');
    let $startDate = $('#start_date');
    let $endDate = $('#end_date');
    let localeAbbreviation = locale.substr(0, 2);

    i18next.use(Backend)
        .init({
            lng: locale,
            resources: dateRangepickerLocale
        });

    $dateRange.daterangepicker({
        opens: open,
        autoUpdateInput: false,
        showDropdowns: true,
        maxDate: moment(),
        locale: i18next.t('locale', {returnObjects: true}),
        ranges: {
            [i18next.t('last_30_days')]: [moment().subtract(29, 'days'), moment()],
            [i18next.t('this_month')]: [moment().startOf('month'), moment().endOf('month')],
            [i18next.t('last_month')]: [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, (start, end) => {
        $('#daterange').val(start.locale(localeAbbreviation).format('D MMM YYYY') + ' - ' + end.locale(localeAbbreviation).format('D MMM YYYY'));
    });

    $('#daterange').val(moment().locale(localeAbbreviation).format('D MMM YYYY') + ' - ' + moment().locale(localeAbbreviation).format('D MMM YYYY'));
    $startDate.val(moment().format('YYYY-MM-DD'));
    $endDate.val(moment().format('YYYY-MM-DD'));

    $dateRange.on('apply.daterangepicker', (event, picker) => {
        $startDate.val(picker.startDate.format('YYYY-MM-DD'));
        $endDate.val(picker.endDate.format('YYYY-MM-DD'));
    });
};
// Init date range picker end today
let initDateRangePickerEndToday = (open = 'left') => {
    let locale = getCookie('language-js');
    locale = (locale === null || locale === '') ? 'en_US' : locale;
    let $dateRange = $('.daterange');
    let $startDate = $('#start_date');
    let $endDate = $('#end_date');
    let localeAbbreviation = locale.substr(0, 2);

    i18next.use(Backend)
        .init({
            lng: locale,
            resources: dateRangepickerLocale
        });

    $dateRange.daterangepicker({
        opens: open,
        autoUpdateInput: false,
        showDropdowns: true,
        maxDate: moment(),
        locale: i18next.t('locale', {returnObjects: true}),
        ranges: {
            [i18next.t('today')]: [moment(), moment()],
            [i18next.t('yesterday')]: [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            [i18next.t('last_7_days')]: [moment().subtract(6, 'days'), moment()],
            [i18next.t('last_30_days')]: [moment().subtract(29, 'days'), moment()],
            [i18next.t('this_month')]: [moment().startOf('month'), moment().endOf('month')],
            [i18next.t('last_month')]: [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, (start, end) => {
        $('#daterange').val(start.locale(localeAbbreviation).format('D MMM YYYY') + ' - ' + end.locale(localeAbbreviation).format('D MMM YYYY'));
    });

    $('#daterange').val(moment().locale(localeAbbreviation).format('D MMM YYYY') + ' - ' + moment().locale(localeAbbreviation).format('D MMM YYYY'));
    $startDate.val(moment().format('YYYY-MM-DD'));
    $endDate.val(moment().format('YYYY-MM-DD'));

    $dateRange.on('apply.daterangepicker', (event, picker) => {
        $startDate.val(picker.startDate.format('YYYY-MM-DD'));
        $endDate.val(picker.endDate.format('YYYY-MM-DD'));
    });
};

// Init date range picker end today without date
let initDateRangePickerEndTodayWithoutDate = (open = 'left') => {
    let locale = getCookie('language-js');
    locale = (locale === null || locale === '') ? 'en_US' : locale;
    let $dateRange = $('.daterange');
    let $startDate = $('#start_date');
    let $endDate = $('#end_date');
    let localeAbbreviation = locale.substr(0, 2);

    i18next.use(Backend)
        .init({
            lng: locale,
            resources: dateRangepickerLocale
        });

    $dateRange.daterangepicker({
        opens: open,
        autoUpdateInput: false,
        showDropdowns: true,
        maxDate: moment(),
        locale: i18next.t('locale', {returnObjects: true}),
        ranges: {
            [i18next.t('today')]: [moment(), moment()],
            [i18next.t('yesterday')]: [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            [i18next.t('last_7_days')]: [moment().subtract(6, 'days'), moment()],
            [i18next.t('last_30_days')]: [moment().subtract(29, 'days'), moment()],
            [i18next.t('this_month')]: [moment().startOf('month'), moment().endOf('month')],
            [i18next.t('last_month')]: [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, (start, end) => {
        $('#daterange').val(start.locale(localeAbbreviation).format('D MMM YYYY') + ' - ' + end.locale(localeAbbreviation).format('D MMM YYYY'));
    });

    $dateRange.on('apply.daterangepicker', (event, picker) => {
        $startDate.val(picker.startDate.format('YYYY-MM-DD'));
        $endDate.val(picker.endDate.format('YYYY-MM-DD'));
    });
};

// Init date range picker with time
let initDateRangePickerWithTime = (open = 'left') => {
    let locale = getCookie('language-js');
    locale = (locale === null || locale === '') ? 'en_US' : locale;
    let $dateRange = $('.daterange');
    let $startDate = $('#start_date');
    let $endDate = $('#end_date');
    let localeAbbreviation = locale.substr(0, 2);

    i18next.use(Backend)
        .init({
            lng: locale,
            resources: dateRangepickerLocale
        });

    $dateRange.daterangepicker({
        opens: open,
        timePicker: true,
        autoUpdateInput: false,
        showDropdowns: true,
        maxDate: moment(),
        locale: i18next.t('locale', {returnObjects: true}),
    }, (start, end) => {
        $('#daterange').val(start.locale(localeAbbreviation).format('D MMM YYYY hh:mm A') + ' - ' + end.locale(localeAbbreviation).format('D MMM YYYY hh:mm A'));
    });

    $('#daterange').val(moment().locale(localeAbbreviation).format('D MMM YYYY hh:mm A') + ' - ' + moment().locale(localeAbbreviation).format('D MMM YYYY hh:mm A'));
    $startDate.val(moment().format('YYYY-MM-DD hh:mm A'));
    $endDate.val(moment().format('YYYY-MM-DD hh:mm A'));

    $dateRange.on('apply.daterangepicker', (event, picker) => {
        $startDate.val(picker.startDate.format('YYYY-MM-DD hh:mm A'));
        $endDate.val(picker.endDate.format('YYYY-MM-DD hh:mm A'));
    });
};

// Init lite picker end today
let initLitepickerEndToday = () => {
    let locale = getCookie('language-js');
    locale = locale.replace('_', '-');

    return new Litepicker({
        element: document.getElementById('date_range'),
        autoRefresh:true,
        format: 'DD/MM/YYYY',
        singleMode: false,
        startDate: moment(),
        endDate: moment(),
        maxDate: moment(),
        numberOfMonths: 2,
        numberOfColumns: 2,
        showTooltip: false,
        lang: locale
    });
};

// Init lite picker end today with class
let initLitepickerEndTodayNew = () => {
    let locale = getCookie('language-js');
    locale = locale.replace('_', '-');

    return new Litepicker({
        element: document.getElementById('date_range_new'),
        autoRefresh:true,
        format: 'DD/MM/YYYY',
        singleMode: false,
        startDate: moment(),
        endDate: moment(),
        maxDate: moment(),
        numberOfMonths: 2,
        numberOfColumns: 2,
        showTooltip: false,
        lang: locale
    });
};

// Init file input
let initFileInput = (preview, field) => {
    let locale = getCookie('language-js');
    locale = (locale === null || locale === '') ? 'en_US' : locale;
    locale = locale.substr(0, 2);

    let config = {
        language: locale,
        theme: 'fa6',
        showCaption: false,
        showRemove: false,
        showUpload: false,
        showCancel: false,
        showClose: false,
        dropZoneEnabled: false,
        allowedFileExtensions: ['png', 'jpg', 'jpeg', 'gif', 'webp', 'xlsx'],
        maxFileSize: 5120,
        maxImageWidth: 3440
    };

    if (preview !== undefined) {
        config.defaultPreviewContent = preview;
    }

    if (field !== undefined) {
        $(`#${field}`).fileinput(config);

    } else {
        $('input[type="file"]').fileinput(config);
    }
};

// Init select2
let initSelect2 = () => {
    $('select').select2({
        width: '100%'
    });
};

// Init tinyMCE
let initTinyMCE = () => {
    tinymce.init({
        selector: '#content',
        height: 580,
        theme: 'silver',
        plugins: [
            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'emoticons template paste textcolor colorpicker textpattern imagetools',

        ],
        toolbar1: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link fullscreen',
        image_advtab: true,
        browser_spellcheck: true,
        relative_urls: true
    });

    tinymce.init({
        selector: '#additional_content',
        height: 550,
        theme: 'silver',
        plugins: [
            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'emoticons template paste textcolor colorpicker textpattern imagetools',

        ],
        toolbar1: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link fullscreen',
        image_advtab: true,
        browser_spellcheck: true,
        relative_urls: true
    });

    tinymce.init({
        selector: '#steps_content',
        height: 550,
        theme: 'silver',
        plugins: [
            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'emoticons template paste textcolor colorpicker textpattern imagetools',

        ],
        toolbar1: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link fullscreen',
        image_advtab: true,
        browser_spellcheck: true,
        relative_urls: true
    });

    tinymce.init({
        selector: '#terms_content',
        height: 550,
        theme: 'silver',
        plugins: [
            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'emoticons template paste textcolor colorpicker textpattern imagetools',

        ],
        toolbar1: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link fullscreen',
        image_advtab: true,
        browser_spellcheck: true,
        relative_urls: true
    });
};

// Generate random password
let randomPassword = (length) => {
    const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    const numbers = '1234567890';
    let lettersLength = Math.trunc(length * 0.7);
    let numbersLength =  Math.trunc(length * 0.3);
    lettersLength += (length - (lettersLength + numbersLength));
    let password = [];

    for (let i = 0; i < lettersLength; i++) {
        let number = Math.floor(Math.random() * letters.length);
        password.push(letters.substring(number, number + 1));
    }

    for (let i = 0; i < numbersLength; i++) {
        let number = Math.floor(Math.random() * numbers.length);
        password.push(numbers.substring(number, number + 1));
    }
    password = password.sort(() => {
        return Math.random() - 0.5
    }).join('');
    $('input[name="password"]').val(password);
}

// Refresh random password
let refreshRandomPassword = (length = 10) => {
    randomPassword(length);

    $('.refresh-password').click(() => {
        randomPassword(length);
    })
}

// Init repeater
let initRepeater = (list) => {
    let repeater = $('.repeater').repeater({
        isFirstItemUndeletable: true
    });
    if (list !== undefined) {
        repeater.setList(list);
    }
};

// Set cookie
let setCookie = (name, value, days) => {
    let d = new Date();
    d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
    let expires = "expires=" + d.toUTCString();
    document.cookie = name + "=" + value + ";" + expires + ";path=/";
};

export {
    activeMenu,
    clearForm,
    getCookie,
    initDatepickerEndToday,
    initDatepickerStartToday,
    initDateRangePickerEndToday,
    initDateRangePickerEndMonth,
    initLitepickerEndToday,
    initLitepickerEndTodayNew,
    initFileInput,
    initSelect2,
    initTinyMCE,
    setCookie,
    initDateTimePicker,
    refreshRandomPassword,
    initDateRangePickerWithTime,
    initDateRangePickerEndTodayWithoutDate,
    initRepeater
};
