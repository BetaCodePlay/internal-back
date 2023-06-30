import {initSelect2, setCookie} from "./commons";
import {swalError, swalSuccessNoButton, swalSuccessWithButton} from "../../commons/js/core";

class Core {
    // Exchange rates
    exchangeRates() {
        let $table = $('#exchange-rates-table');
        let $button = $('.update-exchange');

        $table.DataTable({
            "order": []
        });

        $('.update-exchange').click(function () {
            $button.button('loading');
            let rate = $(this).data('rate');
            let amount = $(`#rate-${rate}`).val();

            $.ajax({
                url: $table.data('route'),
                type: 'post',
                dataType: 'json',
                data: {rate, amount}

            }).done(function (json) {
                $('#updated').text(json.data.updated);
                swalSuccessWithButton(json);

            }).fail(function (json) {
                swalError(json);

            }).always(function () {
                $button.button('reset');
            });
        });
    }

    // Get reset email
    resetEmail() {
        $(document).ready(function() {
            $('#reset-email-modal').modal('show');
          });
    }

    //Upload states
    states(state){
        initSelect2();
        $('#country').on('change', function () {
            let country = $(this).val();
            let route = $(this).data('route');
            let states = $('#state');
            if (country !== '') {
                $.ajax({
                    url: route,
                    type: 'get',
                    dataType: 'json',
                    data: {
                        country
                    }
                }).done(function (json) {
                    $('#state option[value!=""]').remove();
                    $(json.data.states).each(function (key, element) {
                        states.append("<option value=" + element.iso2 + ">" + element.name + "</option>");
                    })
                    states.prop('disabled', false);
                    states.val(state).trigger('change');

                }).fail(function (json) {

                });
            } else {
                states.val('');
            }
        }).trigger('change');
    }

    // Upload city
    city(city){
        initSelect2();
        $('#state').on('change', function () {
            let country = $('#country').val();
            let states = $(this).val();
            let route = $(this).data('route');
            let cities = $('#city');

            if (states !== '') {
                $.ajax({
                    url: route,
                    type: 'get',
                    dataType: 'json',
                    data: {
                        country,
                        states,
                    }
                }).done(function (json) {
                    $('#city option[value!=""]').remove();
                    $(json.data.city).each(function (key, element) {
                        cities.append("<option value=" + element.name + ">" + element.name + "</option>");
                    })
                    cities.prop('disabled', false);
                    cities.val(city);
                }).fail(function (json) {

                });
            } else {
                cities.val('');
            }
        });
    }

}

window.Core = Core;
