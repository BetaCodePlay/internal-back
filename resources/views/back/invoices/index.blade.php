<!DOCTYPE html>
<html>
<head>
    <title>{{$data['title']}}</title>
    <style type="text/css">
        table {
            margin-left:30px;
            margin-right:30px;
        }
        .footer {
            color: #000000;
            width: 100%;
            height: 30px;
            position: absolute;
            bottom: 0;
            padding: 8px 0;
            text-align: left;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body style="background: #FFFFFF;">
<table width="100%">
    <thead>
    <tr>
        <td>
            <span
                style="font-family: Arial, Helvetica, sans-serif; font-size:18px; color: #000000"> <strong>{{$data['title']}}</strong></span><br>
        </td>
    </tr>
    </thead>
</table>
<table width="100%" style="margin-top: 30px">
    <thead>
    <tr>
        <td style="padding-bottom:10px;">
            <div class="col-xs-5" style="font-family: Arial, Helvetica, sans-serif; font-size:12px;">
                <span>{{$data['data_client']['sub_title_client']}}</span>
                <br>
                <span>{{$data['data_client']['messrs']}}</span>
                <br>
                <span>{{$data['data_client']['attention']}}</span>
                <br>
                <span>{{$data['data_client']['email']}}</span>
                <br>
                <span>{{$data['data_client']['phone']}}</span>
                <br>
            </div>
        </td>
        <td>
            <div style="padding: 0px 0px 0px 40px;">
                <img style="padding-left: 40px; margin-top: 0px;" width="200"
                     src="https://dotworkers.com/img/logo-light.png" alt="dotworkers">
            </div>
        </td>
    </tr>
    </thead>
</table>
<table width="100%" style="border-collapse: collapse">
    <thead>
    <tr style="background: #999999; font-family: Arial, Helvetica, sans-serif; font-size:16px;">
        <th style="text-align:center; width:60%; border: black 1px solid">
            {{$data['table_title']['description']}}
        </th>
        <th style="text-align:center; width:20%; border: black 1px solid">
            {{$data['table_title']['units']}}
        </th>
        <th style="text-align:center; width:20%; border: black 1px solid">
            {{$data['table_title']['total']}}
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach($data['invoices']['invoices_service'] as $invoice)
        <tr style="background: #e06666; font-family: Arial, Helvetica, sans-serif; font-size:14px;">
            <td style="text-align:left; width:60%; border: black 1px solid">{{$invoice['description']}}</td>
            <td style="text-align:center; width:20%; border: black 1px solid">{{$invoice['unit']}}</td>
            <td style="text-align:center; width:20%; border: black 1px solid">{{$data['invoices_details']['sub_total']}}</td>
        </tr>
        <tr style="background: #e62154; font-family: Arial, Helvetica, sans-serif; font-size:16px;">
            <td style="background: #FFFFFF; text-align:center; width:60%; height: 15px; border-left: black 1px solid;"></td>
            <td style="background: #FFFFFF; text-align:center; width:20%; height: 15px;"></td>
            <td style="background: #FFFFFF; text-align:center; width:20%; height: 15px; border-right: black 1px solid;"></td>
        </tr>
    @endforeach
    <tr style="background: #e62154; font-family: Arial, Helvetica, sans-serif; font-size:16px;">
        <td style="background: #FFFFFF; text-align:center; width:60%; height: 10px; border-left: black 1px solid;"></td>
        <td style="background: #FFFFFF; text-align:center; width:20%; height: 10px;"></td>
        <td style="background: #FFFFFF; text-align:center; width:20%; height: 10px; border-right: black 1px solid;"></td>
    </tr>
    <tr style="background: #999999; font-family: Arial, Helvetica, sans-serif;">
        <td style="text-align:center; width:60%; border-left: black 1px solid; border-top: black 1px solid; border-bottom: black 1px solid;"></td>
        <td style="text-align:right; width:20%; border-left: none; border-top: black 1px solid; border-bottom: black 1px solid; font-size:16px;">{{$data['invoices']['sub_total_title']}}</td>
        <td style="text-align:center; width:20%; border-right: black 1px solid; border-left: black 1px solid; border-bottom: black 1px solid; border-top: black 1px solid; font-size:16px;">{{$data['invoices_details']['sub_total']}}</td>
    </tr>
    <tr style="background:#e06666; font-family: Arial, Helvetica, sans-serif; font-size:16px;">
        <td style="background: #FFFFFF; text-align:center; width:60%; height: 25px; border-left: black 1px solid;"></td>
        <td style="background: #FFFFFF; text-align:center; width:20%; height: 25px;"></td>
        <td style="background: #FFFFFF; text-align:center; width:20%; height: 25px; border-right: black 1px solid;"></td>
    </tr>
    <tr style="background: #999999; font-family: Arial, Helvetica, sans-serif;">
        <td style="text-align:center; width:60%; border-left: black 1px solid; border-top: black 1px solid; border-bottom: black 1px solid;"></td>
        <td style="text-align:right; width:20%; border-left: none; border-top: black 1px solid; border-bottom: black 1px solid; font-size:16px;">{{$data['invoices']['total_title']}}</td>
        <td style="text-align:center; width:20%; border-right: black 1px solid; border-left: black 1px solid; border-bottom: black 1px solid; border-top: black 1px solid; font-size:16px;">{{$data['invoices_details']['sub_total']}}</td>
    </tr>
    </tbody>
</table>
<table width="100%" style="margin-top: 20px">
    <tbody>
    <tr>
        <td style="padding-bottom:10px;">
            <div class="col-xs-5" style="font-family: Arial, Helvetica, sans-serif; font-size:18px; color: #000000">
                <span>{{ $data['payment_methods_title']}}</span>
            </div>
        </td>
    </tr>
    </tbody>
</table>
<table width="100px" style="border-collapse: collapse">
    @foreach($data['payment_methods'] as $payment_method)
        <thead>
        <tr style="width: 640px; background: #999999; font-family: Arial, Helvetica, sans-serif; font-size:16px; ">
            <th style="text-align:center; width: 640px; border-left: black 1px solid; border-right: black 1px solid; border-top: black 1px solid">
               <span> <strong> {{$payment_method['payment']}} </strong></span>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr style="font-family: Arial, Helvetica, sans-serif; font-size:14px; width: 630px; border: black 1px solid; border-bottom: white 1px solid;">
            <td style="background: #FFFFFF; width: 630px; height: 20px; border:black 1px solid;  border-top: none;">
                @switch($payment_method['payment_method_type'])
                    @case(\Dotworkers\Configurations\Enums\PaymentMethods::$wire_transfers)
                    <div  style="font-family: Arial, Helvetica, sans-serif; font-size:12px; height: 50px ">
                        <div style="float: left; display:inline; margin: 4px">
                            <span> <strong>{{$payment_method['data_title']['bank']}}: {{$payment_method['data']['bank']}}</strong></span>
                            <br>
                            <span><strong>{{$payment_method['data_title']['name']}}: {{$payment_method['data']['name']}}</strong></span>
                            <br>
                            <span><strong>{{$payment_method['data_title']['email']}}: {{$payment_method['data']['email']}}</strong></span>
                        </div>
                        <div style=" float: left; display:inline;  margin: 1px">
                            <span> <strong>{{$payment_method['data_title']['account_number']}}: {{$payment_method['data']['account_number']}}</strong></span>
                            <br>
                            <span><strong>{{$payment_method['data_title']['dni']}}: {{$payment_method['data']['dni']}}</strong></span>
                            <br>
                            <span><strong>{{$payment_method['data_title']['type_account']}}: {{$payment_method['data']['type_account']}}</strong></span>
                        </div>
                    </div>
                    @break
                    @case(\Dotworkers\Configurations\Enums\PaymentMethods::$zelle)
                    <div class="col-xs-5" style="font-family: Arial, Helvetica, sans-serif; font-size:12px;">
                        <span> <strong>{{$payment_method['data_title']['name']}}: {{$payment_method['data']['name']}}</strong></span>
                        <br>
                        <span> <strong>{{$payment_method['data_title']['email']}}: {{$payment_method['data']['email']}}</strong></span>
                    </div>
                    @break
                    @case(\Dotworkers\Configurations\Enums\PaymentMethods::$paypal)
                    <div class="col-xs-5" style="font-family: Arial, Helvetica, sans-serif; font-size:12px;">
                        <span> <strong>{{$payment_method['data_title']['name']}}: {{$payment_method['data']['name']}}</strong></span>
                        <br>
                        <span> <strong>{{$payment_method['data_title']['email']}}: {{$payment_method['data']['email']}}</strong></span>
                    </div>
                    @break
                    @case(\Dotworkers\Configurations\Enums\PaymentMethods::$uphold)
                    @case(\Dotworkers\Configurations\Enums\PaymentMethods::$cryptocurrencies)
                    <div class="col-xs-5" style="font-family: Arial, Helvetica, sans-serif; font-size:12px;">
                        <span> <strong>{{$payment_method['data_title']['email']}}: {{$payment_method['data']['email']}}</strong></span>
                    </div>
                    @break
                @endswitch
            </td>
        </tr>
        <tr style="background: #e06666; font-family: Arial, Helvetica, sans-serif; font-size:16px; width: 650px;">
            <td style="background: #FFFFFF; text-align:center; width: 650px; height: 15px;"></td>
        </tr>
        </tbody>
    @endforeach
</table>
<br>
<div class="footer" style="font-family: Arial, Helvetica, sans-serif; font-size:12px;">
    <span>{{$data['date']}}</span>
</div>
<div class="page-break"></div>
<table width="100%" style="margin-top: 30px;">
    <thead>
        <tr>
            <td colspan="6" style="width:75%; font-family: Arial, Helvetica, sans-serif; font-size:12px;">
                <div style="padding-top: 5px;">
                    <span> <strong>Detalles de Factura Nro.######</strong></span>
                </div>
                <div style="padding-top: 5px;">
                    <span><strong>Mes de {{$data['month']}}</strong></span>
                </div>
            </td>
            <td style="width:35%;">
                <div style="padding-left: 50px">
                    <img style="margin-top:20px;" width="150"
                         src="https://dotworkers.com/img/logo-light.png" alt="dotworkers">
                </div>
            </td>
        </tr>
    </thead>
</table>
<table width="100%" style="border-collapse: collapse;">
    <thead>
    <tr style="background: #999999; font-family: Arial, Helvetica, sans-serif; font-size:16px;">
        <th colspan="7" style="text-align:center; border: black 1px solid">
            {{$data['data_client']['sub_title_details']}}
        </th>
    </tr>
    <tr style="background: #999999;  font-family: Arial, Helvetica, sans-serif; font-size:16px;">
        <th colspan="4" style="text-align:center; background: #e06666; font-family: Arial, Helvetica, sans-serif; border: black 1px solid;">
            detalle de factura
        </th>
        <th colspan="2" style="text-align:center; text-align:center; background: #e06666; font-family: Arial, Helvetica, sans-serif; border: black 1px solid; border: black 1px solid;">
            porcentaje
        </th>
        <th rowspan="2" style="text-align:center;  border: black 1px solid">
            Monto a facturar
        </th>
    </tr>
    <tr style=" background: #e06666; font-family: Arial, Helvetica, sans-serif; font-size:14px;">
        <th style="text-align:center;  border: black 1px solid">
            Producto
        </th>
        <th style="text-align:center;  border: black 1px solid">
            Ventas
        </th>
        <th  style="text-align:center;  border: black 1px solid">
            Premios
        </th>
        <th  style="text-align:center;  border: black 1px solid">
            Profit
        </th>
        <th  style="text-align:center;  border: black 1px solid">
           % Ventas
        </th>
        <th  style="text-align:center;  border: black 1px solid">
           % Profit
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach($data['invoices_details']['totals'] as $invoice_detail)
        <tr style="font-family: Arial, Helvetica, sans-serif; font-size:14px; border: black 1px solid">
            <td style="text-align:left; border-left: black 1px solid; border-top: #dee2e6 1px solid">{{$invoice_detail->provider}}</td>
            <td style="text-align:center; border-left: #dee2e6 1px solid; border-top: #dee2e6 1px solid">{{$invoice_detail->played}}</td>
            <td style="text-align:center; border-left:#dee2e6 1px solid; border-top: #dee2e6 1px solid">{{$invoice_detail->won}}</td>
            <td style="text-align:center; border-left: #dee2e6 1px solid; border-top: #dee2e6 1px solid">{{$invoice_detail->profit}}</td>
            <td style="text-align:center; border-left: #dee2e6 1px solid; border-top: #dee2e6 1px solid"></td>
            <td style="text-align:center; border-left: #dee2e6 1px solid; border-top: #dee2e6 1px solid">{{$invoice_detail->percentage}}</td>
            <td style="text-align:center; border-left: #dee2e6 1px solid; border-top: #dee2e6 1px solid; border-right: black 1px solid;">{{$invoice_detail->total_provider}}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr style="font-family: Arial, Helvetica, sans-serif; text-align:center; font-size:14px;">
        <th colspan="6" style=" border-left: black 1px solid; border-right: black 1px solid; border-top: black 1px solid"></th>
        <th colspan="1" style="background: #999999; border: black 1px solid; border-left: black 1px solid; border-top: black 1px solid;">{{$data['invoices_details']['sub_total']}}</th>
    </tr>
    <tr style="font-family: Arial, Helvetica, sans-serif; text-align:center; font-size:14px; border: black 1px solid">
        <th colspan="5" style="border: black 1px solid; border-top:none;"></th>
        <th colspan="1" style="background: #999999; border-left: black 1px solid; border-top: black 1px solid; border-bottom: black 1px solid;">{{$data['table_title']['totals']}}</th>
        <th colspan="1" style="background: #999999; border-right: black 1px solid; border-top: black 1px solid; border-bottom: black 1px solid;">{{$data['invoices_details']['total_convert']}}</th>
    </tr>
    </tfoot>
</table>
<div class="footer" style="font-family: Arial, Helvetica, sans-serif; font-size:12px;">
    <span>{{$data['date']}}</span>
</div>
</body>
</html>
