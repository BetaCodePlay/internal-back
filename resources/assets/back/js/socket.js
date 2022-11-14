import Echo from 'laravel-echo';
import Push from 'push.js';

window.io = require('socket.io-client');

class Socket {

    // Init channel
    initChannel(client, favicon, route) {
        Push.Permission.request();

        window.Echo = new Echo({
            broadcaster: 'socket.io',
            host: 'socket.dev.dotworkers.net',
        });

        window.Echo.private(`credit.${client}`)
            .listen('.betpay.credit', json => {
                console.log(json);
                let data = json.data;
                $.ajax({
                    url: route,
                    method: 'post',
                    data: {
                        payment_method: data.payment_method,
                        currency: data.currency,
                        amount: data.amount
                    },
                }).done(function(json) {
                    $('#notifications-quantity').text(json.data.quantity);
                    $('#notifications-container').html('').append(json.data.notifications)
                })

                if (Push.Permission.has()) {
                    Push.create('Tienes un nuevo depósito', {
                        body: 'Consulta las notificaiones en DotPanel para más detalles',
                        icon: favicon,
                        timeout: 4000,
                        onClick: function () {
                            window.focus();
                            this.close();
                        }
                    });
                }
            });
    }
}

window.Socket = Socket;
