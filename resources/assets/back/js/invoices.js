import {initDateRangePickerEndToday, initSelect2} from "./commons";

class Invoices {

    // All invoices
    getInvoice() {
        initDateRangePickerEndToday(open = 'right');
        initSelect2();
        $(document).on('click', '#search', function () {
            let $whitelabel = $('#whitelabel').val();
            let $provider = $('#provider').val();
            let $currency = $('#currency').val();
            let $convert = $('#convert').val();
            let $startDate = $('#start_date').val();
            let $endDate = $('#end_date').val();
            let $route =`${$(this).data('route')}/${$startDate}/${$endDate}/${$whitelabel}?currency=${$currency}&convert=${$convert}&provider=${$provider}`;
            $.get($route, function(){

            });
        });
    }
}

window.Invoices = Invoices;
