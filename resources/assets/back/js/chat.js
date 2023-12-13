
Echo.private(`chat.2`)
    .listen('MessageSent', (e) => {
        console.log('MessageSent', e);
    })
    .error((error) => {
        console.error('Error en la escucha del evento:', error);
    });
