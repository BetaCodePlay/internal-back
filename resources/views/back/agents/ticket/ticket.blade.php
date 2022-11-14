<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{$filename}}</title>
    {{-- <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet"> --}}
    <style type="text/css">
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }
        a {
            color: #5D6975;
            text-decoration: underline;
        }
        body {
            position: relative;
            margin: 0 auto;
            color: #001028;
            background: #FFFFFF;
            font-family: 'Lato', sans-serif;
            font-size: 12px;
        }
        .header {
            padding: 10px 0;
            margin-bottom: 30px;
        }
        #logo {
            text-align: center;
            margin-bottom: 10px;
        }
        #logo img {
            width: 150px;
        }
        h1 {
            border-top: 1px solid  #5D6975;
            border-bottom: 1px solid  #5D6975;
            color: #5D6975;
            font-size: 2.4em;
            line-height: 1.4em;
            font-weight: normal;
            text-align: center;
            margin: 0 0 20px 0;
            background: url(dimension.png);
        }
        #project {
            float: left;
        }
        #project span {
            color: #5D6975;
            text-align: left;
            width: 52px;
            margin-right: 10px;
            display: inline-block;
            font-size: 24px;
        }
        #project div{
            font-size: 24px;
        }
        #company {
            float: right;
            text-align: center;
        }
        #project div,
        #company div {
            white-space: nowrap;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 20px;
        }
        table:last-of-type{
            margin-bottom:3rem;
        }
        table tr:nth-child(2n-1) td {
            background: #F5F5F5;
        }
        table th,
        table td {
            text-align: left;
        }
        table th {
            padding: 5px 20px;
            color: #5D6975;
            border-bottom: 1px solid #C1CED9;
            white-space: nowrap;
            font-weight: normal;
        }
        table td.p-0{
            padding: none;
        }
        table td{
            padding: 20px;
        }
        table .v-align-top {
            vertical-align: top;
        }
        table td.grand {
            border-top: 1px solid #5D6975;;
        }
        #notices .notice {
            color: #5D6975;
            font-size: 1.2em;
        }
        footer {
            color: #5D6975;
            width: 100%;
            height: 30px;
            position: absolute;
            bottom: 0;
            border-top: 1px solid #C1CED9;
            padding: 8px 0;
            text-align: center;
        }
        tr:nth-last-child(-n+1) td{
            border-bottom: 1px solid #5D6975;
        }
        .page-break{
            page-break-after: always;
        }
        h5{
            margin-bottom:1rem;
        }
        h5:first-child{
            margin-top:none;
        }
    </style>
</head>
<body>
<div class="header clearfix">
    <div id="logo">
        <img src="{{asset('auth/img/dotpanel-logo.png')}}" alt="Dotpanel">
    </div>
</div>
<div>
    <h1>{{_i('transaction ticket')}}</h1>
    <table>
        <thead>
        <tr>
            <th class="ref">{{_i('Username')}}</th>
            <th class="service">{{_i('Transaction type')}}</th>
            <th class="desc">{{_i('Currency')}}</th>
            <th>{{_i('Amount')}}</th>
            <th>{{_i('From')}}</th>
            <th>{{_i('To')}}</th>
        </tr>
        </thead>
        <tbody>
              <tr>
                  <td class="ref">{{$ticket->username}}</td>
                  <td class="service">{{$ticket->type}}</td>
                  <td class="desc">{{$ticket->currency_iso}}</td>
                  <td class="unit">{{$ticket->amount}}</td>
                  <td class="qty">{{$ticket->from}}</td>
                  <td class="qty">{{$ticket->to}}</td>
              </tr>
        </tbody>
    </table>
</div>

</body>
</html>
