
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style type="text/css">
      @import "https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap";

      html, body {
          margin: 0;
          font-family: 'Montserrat', sans-serif;
          line-height: 1.42857143;
      }

      * {
          -webkit-box-sizing: border-box;
          -moz-box-sizing: border-box;
          box-sizing: border-box;
      }

      a, a:hover, a:focus, a:active {
          text-decoration: none;
          -webkit-transition: all 0.25s ease;
          transition: all 0.25s ease;
      }

      p {
          margin-top: 0;
      }

      ul {
          margin: 0;
      }

      .text-center {
          text-align: center;
      }

      .text-gray {
          color: #999;
      }

      .btn {
          display: inline-block;
          padding: 8px 25px;
          font-size: 15px;
          line-height: 1.42857143;
          text-align: center;
          white-space: nowrap;
          vertical-align: middle;
          -ms-touch-action: manipulation;
          touch-action: manipulation;
          cursor: pointer;
          -webkit-user-select: none;
          -moz-user-select: none;
          -ms-user-select: none;
          user-select: none;
          background-image: none;
          border: 1px solid transparent;
          border-radius: 5px;
          -webkit-transition: all 0.25s ease;
          transition: all 0.25s ease;
          font-weight: 600;
          font-family: 'Montserrat', sans-serif;
          margin: 5px 0;
      }

      .btn.success {
          color: #fff;
          background: #1dbb1d;
          border-color: #1dbb1d;
      }

      .btn.success:hover, .btn.success:focus {
          color: #fff;
          background: #29da29;
          border-color: #29da29;
      }

      .btn.cancel {
          color: #fff;
          background: #aaa;
          border-color: #aaa;
      }

      .btn.cancel:hover, .btn.cancel:focus {
          color: #fff;
          background: #ccc;
          border-color: #ccc;
      }

      .btn.error {
          color: #fff;
          background: #dd4f4f;
          border-color: #dd4f4f;
      }

      .btn.error:hover, .btn.error:focus {
          color: #fff;
          background: #ee6666;
          border-color: #ee6666;
      }

      .body {
          width: 800px;
          max-width: 100%;
          margin: auto;
          background: #fff;
          box-shadow: 0 0 4px #aaa;
      }

      .body .header {
          background: linear-gradient(0,transparent 20%, #1e7e9b 20%);
          text-align: center;
          padding-top: 40px;
      }

      .body .header .symbol {
          padding: 10px 20px;
          border-radius: 100px;
          border: 8px solid #1e7e9b;
          background: #fff;
          display: inline-block;
      }

      .body .header .symbol img {
          max-height: 80px;
      }

      .content {
          padding: 70px 15px 0 15px;
      }

      .content .title {
          text-align: center;
          font-weight: 600;
          font-size: 26px;
          color: #444;
          margin-bottom: 30px;
          margin-top: 10px;
      }

      .content .title.success {
           color: #1dbb1d;
       }

      .content .title.error {
          color: #dd4f4f;
      }

      .content .logo {
          text-align: center;
          margin: 25px 0;
      }

      .content .logo img {
          max-width: 75px;
      }

      .content .information {
          text-align: center;
          max-width: 600px;
          margin: auto;
          color: #666;
      }

      .content .information p {
          margin-bottom: 20px;
      }

      .content .information .welcome {
          font-weight: 600;
          font-size: 20px;
          margin-bottom: 20px;
          color: #444;
      }

      .content .footer {
          text-align: center;
          margin-top: 40px;
          padding-bottom: 30px;
      }

      .content .footer .text {
          font-weight: 600;
          font-size: 18px;
          color: #666;
          margin-bottom: 15px;
      }

      .social a {
          color: #1e7e9b;
          margin: 5px;
          font-size: 36px;
          transition: 0.3s;
          display: inline-block;
          line-height: 1;
      }

      .social a:hover, .social a:focus {
          transform: scale(1.1);
      }

      @media screen and (max-width: 767px) {
          .body .header {
              padding-top: 20px;
          }

          .body .header .symbol img {
              max-height: 60px;
          }
      }
  </style>
</head>
<body>
<div class="body">
    <div class="header">
        <div class="symbol">
            <img src="{{ \Dotworkers\Configurations\Configurations::getLogo()->img_light }}" alt="{{ $whitelabel_description }}">
        </div>
    </div>
    <div class="content">
        <div class="title success">
        @yield('subtitle')
        </div>

        <div class="information">
            <div class="welcome">@yield('title')</div>
            <p>@yield('content')</p>

            <p>@yield('footer')</p>
            <p>{{ $theme }}</p>
        </div>

        <div class="footer">
        {{--<div class="text">Síguenos</div>
            <div class="social">
                <a href="#" target="_blank"><i class="fa fa-telegram" aria-hidden="true"></i></a>
                <a href="#" target="_blank"><i class="fa fa-facebook-square" aria-hidden="true"></i></a>
                <a href="#" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a>
            </div>--}}
        </div>
    </div>
</div>
</body>
</html>
