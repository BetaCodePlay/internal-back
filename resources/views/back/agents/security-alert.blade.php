<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (\Dotworkers\Configurations\Configurations::getWhitelabel() == 109)
        <link rel="shortcut icon" href="{{ asset('commons/img/bloko-favicon.png') }}">
    @else
        <link rel="shortcut icon" href="{{ $favicon }}">
    @endif
    <link rel="apple-touch-icon" sizes="57x57" href="{{ $favicon }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ $favicon }}">
    <title>{{ $title ?? _i('BackOffice') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "Poppins",sans-serif !important;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f0f0f0;
        }

        .card {
            width: 600px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-header {
            background-color: #ffffff;
            color: #333;
            text-align: left;
            padding: 15px;
            border-bottom: 1px solid #ccc;
        }

        .card-header h2 {
            margin: 0;
            font-size: 1.2rem;
        }

        .card-body {
            padding: 20px;
            padding-bottom: 0;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
        }

        input {
            padding: 8px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .card-footer {
            text-align: left;
            padding: 15px;
        }

        button {
            padding: 10px 20px;
            background-color: #66a5e7;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        button:hover {
            background-color: #81b8f2;
        }

        p {
            font-size: 14px;
            color: #666;
            font-weight: 500;
        }

        @media (max-width: 767px) {
            .card {
                width: calc(100% - 30px);
            }
        }
    </style>
</head>

<body>
<div class="card">
    <div class="card-header">
        <h2>Cambiar Usuario</h2>
    </div>
    <div class="card-body">
        <form>
            <label for="name">{{ _i('Username')}}:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">{{ _i('Password')}}:</label>
            <input type="password" id="password" name="password" required>
            <p>Hemos tenido un imprevisto y ahora es requerido que se cambie el nombre de usuario de tu cuenta, gracias y disculpa por los inconvenientes</p>
            <div class="card-footer">
                <button type="button" id="btn-send-username" data-route="{{ route('auth.update-security') }}">Enviar</button>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).on('click', '#btn-send-username', function (){
        let $this = $(this);
        let $route = $this.data('route');

        $.ajax({
            url: $route,
            method: 'POST',
            data: $('form').serialize(),
        }).done(function(response) {
            console.error(response);
            })
            .fail(function(error) {
                console.error(error);
            })
            .always(function() {

            });
    });
</script>
</body>
</html>
